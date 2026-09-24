// Build-time (Node) packages without TypeScript types, used only by vite.config.ts.

type ImageminPlugin = (options?: Record<string, unknown>) => (buffer: Buffer) => Promise<Buffer>;

declare module 'imagemin-mozjpeg' {
    const imageminMozjpeg: ImageminPlugin;
    export default imageminMozjpeg;
}

declare module 'imagemin-webp' {
    const imageminWebp: ImageminPlugin;
    export default imageminWebp;
}

declare module 'imagemin-avif' {
    const imageminAvif: ImageminPlugin;
    export default imageminAvif;
}
