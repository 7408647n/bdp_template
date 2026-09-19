#!/usr/bin/env bun
/**
 * Theme asset build, replacing the TYPO3 extension's vite.config.js.
 *
 * - Compiles assets/src/css/main.css (Tailwind CSS v4) to assets/build/Css/main.css.
 * - Bundles every assets/src/js/entries/*.entry.ts with `Bun.build` into
 *   assets/build/Js/<name>.js (name has the .entry suffix stripped, so
 *   enqueue.php can reference stable file names without hashing).
 *
 * Usage: `bun run scripts/build.ts` or `bun run scripts/build.ts --watch`.
 */

import { existsSync, mkdirSync, rmSync } from "node:fs";
import { join } from "node:path";
import { $ } from "bun";

const ROOT = join(import.meta.dir, "..");
const SRC_CSS = join(ROOT, "assets/src/css/main.css");
const OUT_DIR = join(ROOT, "assets/build");
const OUT_CSS_DIR = join(OUT_DIR, "Css");
const OUT_JS_DIR = join(OUT_DIR, "Js");
const ENTRIES_DIR = join(ROOT, "assets/src/js/entries");

const watch = process.argv.includes("--watch");

async function buildCss(): Promise<void> {
	mkdirSync(OUT_CSS_DIR, { recursive: true });
	const outFile = join(OUT_CSS_DIR, "main.css");
	const args = ["bunx", "@tailwindcss/cli", "-i", SRC_CSS, "-o", outFile];
	if (watch) args.push("--watch");
	if (!watch) args.push("--minify");

	console.log(`[css] ${SRC_CSS} -> ${outFile}`);
	const proc = Bun.spawn(args, { cwd: ROOT, stdout: "inherit", stderr: "inherit" });
	const code = await proc.exited;
	if (code !== 0) {
		throw new Error(`Tailwind CLI exited with code ${code}`);
	}
}

async function buildJs(): Promise<void> {
	mkdirSync(OUT_JS_DIR, { recursive: true });

	const glob = new Bun.Glob("*.entry.ts");
	const entrypoints: string[] = [];
	for await (const file of glob.scan(ENTRIES_DIR)) {
		entrypoints.push(join(ENTRIES_DIR, file));
	}

	if (entrypoints.length === 0) {
		console.warn("[js] no *.entry.ts files found in", ENTRIES_DIR);
		return;
	}

	console.log(`[js] bundling ${entrypoints.length} entr${entrypoints.length === 1 ? "y" : "ies"}`);

	const result = await Bun.build({
		entrypoints,
		outdir: OUT_JS_DIR,
		target: "browser",
		format: "iife",
		splitting: false,
		minify: !watch,
		sourcemap: watch ? "inline" : "none",
		naming: "[dir]/[name].[ext]",
	});

	if (!result.success) {
		for (const message of result.logs) {
			console.error(message);
		}
		throw new Error("bun build failed");
	}

	// Strip the ".entry" suffix so enqueue.php can use stable file names
	// (main.entry.js -> main.js), matching how functions.php references them.
	for (const output of result.outputs) {
		if (output.path.endsWith(".entry.js")) {
			const renamed = output.path.replace(/\.entry\.js$/, ".js");
			await Bun.write(renamed, await Bun.file(output.path).arrayBuffer());
			rmSync(output.path);
		}
	}
}

async function main(): Promise<void> {
	if (!watch && existsSync(OUT_DIR)) {
		rmSync(OUT_DIR, { recursive: true, force: true });
	}
	mkdirSync(OUT_DIR, { recursive: true });

	await Promise.all([buildCss(), buildJs()]);
	console.log("Build complete:", OUT_DIR);
}

main().catch((error) => {
	console.error(error);
	process.exit(1);
});
