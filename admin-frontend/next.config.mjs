// next.config.mjs（ESM）
import { fileURLToPath } from 'url';
import path from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/** @type {import('next').NextConfig} */

const nextConfig = {
  turbopack: {
    root: __dirname,
  },
};
// const nextConfig = {
//   // 親の package-lock.json を見に行かないように、このプロジェクトをルートと明示
//   workspaceRoot: __dirname,
// };

export default nextConfig;

