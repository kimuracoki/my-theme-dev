import { defineConfig } from "vite";
import react from "@vitejs/plugin-react-swc";
import path from "path";

export default defineConfig(({ command }) => {
  const isBuild = command === "build";
  return {
    base: isBuild
      ? "/wp-content/themes/react-ui/dist/" 
      : "/",
    plugins: [react()],
    build: {
      outDir: "dist",
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: path.resolve(__dirname, "index.html"),
        output: {
          assetFileNames: "assets/[name]-[hash][extname]",
          entryFileNames: "assets/[name]-[hash].js",
          chunkFileNames: "assets/[name]-[hash].js",
        },
      },
    },
  };
});
