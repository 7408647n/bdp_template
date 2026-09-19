#!/usr/bin/env bun
/**
 * Build script for the bdp-template WordPress theme.
 *
 * Replaces the TYPO3 sitepackage's Vite build:
 *  1. Compiles the SCSS design system (assets/src/scss/main.scss) to CSS
 *     via the `sass` package (bun build does not compile SCSS itself).
 *  2. Bundles every `*.entry.ts` file with `bun build` into assets/build/js.
 *  3. Copies the lightgallery vendor stylesheet used by the news detail
 *     view (no bundler handles third-party CSS-from-JS imports here).
 *
 * Usage: bun run scripts/build.ts [--watch]
 */
import * as sass from 'sass';
import { existsSync, mkdirSync, readdirSync, writeFileSync, copyFileSync, rmSync } from 'node:fs';
import { join, dirname } from 'node:path';

const ROOT = join(import.meta.dir, '..');
const SCSS_ENTRY = join(ROOT, 'assets/src/scss/main.scss');
const CSS_OUT_DIR = join(ROOT, 'assets/build/css');
const CSS_OUT_FILE = join(CSS_OUT_DIR, 'main.css');
const TS_SRC_DIR = join(ROOT, 'assets/src/ts');
const JS_OUT_DIR = join(ROOT, 'assets/build/js');
const VENDOR_OUT_DIR = join(ROOT, 'assets/build/vendor');

const watch = process.argv.includes('--watch');

function ensureDir(dir: string): void {
  if (!existsSync(dir)) mkdirSync(dir, { recursive: true });
}

function buildCss(): void {
  ensureDir(CSS_OUT_DIR);
  const result = sass.compile(SCSS_ENTRY, {
    style: 'compressed',
    loadPaths: [join(ROOT, 'assets/src/scss'), join(ROOT, 'node_modules')],
    quietDeps: true,
  });
  writeFileSync(CSS_OUT_FILE, result.css);
  console.log(`[sass] wrote ${CSS_OUT_FILE} (${result.css.length} bytes)`);
}

function findEntryFiles(): string[] {
  return readdirSync(TS_SRC_DIR)
    .filter((file) => file.endsWith('.entry.ts'))
    .map((file) => join(TS_SRC_DIR, file));
}

async function buildJs(): Promise<void> {
  ensureDir(JS_OUT_DIR);
  const entrypoints = findEntryFiles();

  const result = await Bun.build({
    entrypoints,
    outdir: JS_OUT_DIR,
    target: 'browser',
    format: 'esm',
    minify: true,
    sourcemap: 'external',
    naming: '[name].js',
  });

  if (!result.success) {
    for (const message of result.logs) {
      console.error(message);
    }
    throw new Error('bun build failed');
  }

  console.log(`[bun build] wrote ${result.outputs.length} file(s) to ${JS_OUT_DIR}`);
}

function copyVendorAssets(): void {
  ensureDir(VENDOR_OUT_DIR);
  const lgCssDir = join(ROOT, 'node_modules/lightgallery/css');
  const targetDir = join(VENDOR_OUT_DIR, 'lightgallery');
  ensureDir(targetDir);

  if (existsSync(join(lgCssDir, 'lightgallery-bundle.min.css'))) {
    copyFileSync(
      join(lgCssDir, 'lightgallery-bundle.min.css'),
      join(targetDir, 'lightgallery-bundle.min.css')
    );
    console.log('[vendor] copied lightgallery-bundle.min.css');
  } else {
    console.warn('[vendor] lightgallery css not found - run `bun install` first');
  }
}

async function build(): Promise<void> {
  buildCss();
  await buildJs();
  copyVendorAssets();
}

if (watch) {
  console.log('Watching assets/src for changes...');
  await build();
  const { watch: fsWatch } = await import('node:fs');
  fsWatch(join(ROOT, 'assets/src'), { recursive: true }, async () => {
    try {
      await build();
    } catch (err) {
      console.error(err);
    }
  });
} else {
  await build();
}
