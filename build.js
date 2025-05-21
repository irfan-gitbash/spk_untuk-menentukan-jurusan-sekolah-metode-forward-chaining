const fs = require("fs-extra");
const { execSync } = require("child_process");
const path = require("path");

// Configuration
const config = {
  sourceDir: ".",
  outputDir: "dist",
  pages: [
    "index.php",
    "konsultasi.php",
    "jurusan.php",
    "tentang.php",
    "hasiloutput.php",
  ],
  assets: ["assets"],
  port: 8000,
};

// Helper functions
function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function startPHPServer() {
  console.log("Starting PHP server...");
  return execSync(`php -S localhost:${config.port}`, {
    stdio: ["pipe", "pipe", "pipe"],
    detached: true,
  });
}

async function convertPages(pages) {
  console.log("Converting PHP pages to static HTML...");
  for (const page of pages) {
    try {
      console.log(`Processing ${page}...`);
      const html = execSync(`curl http://localhost:${config.port}/${page}`);
      const staticFile = page.replace(".php", ".html");
      const outputPath = path.join(config.outputDir, staticFile);
      await fs.writeFile(outputPath, html);
      console.log(`Created ${staticFile}`);
    } catch (error) {
      console.error(`Error processing ${page}:`, error.message);
    }
  }
}

async function createNetlifyRedirects() {
  console.log("Creating Netlify redirects...");
  const redirects = config.pages
    .map((page) => `/${page} /${page.replace(".php", ".html")} 200`)
    .join("\n");
  await fs.writeFile(path.join(config.outputDir, "_redirects"), redirects);
}

async function createVercelConfig() {
  if (!(await fs.pathExists("vercel.json"))) {
    console.log("Creating Vercel configuration...");
    const vercelConfig = {
      version: 2,
      builds: [{ src: "dist/**", use: "@vercel/static" }],
      routes: [{ src: "/(.*)", dest: "/dist/$1" }],
    };
    await fs.writeFile("vercel.json", JSON.stringify(vercelConfig, null, 2));
  }
}

// Main build function
async function build() {
  try {
    // Clean and create output directory
    console.log("Preparing output directory...");
    await fs.emptyDir(config.outputDir);

    // Copy assets
    console.log("Copying assets...");
    for (const asset of config.assets) {
      const source = path.join(config.sourceDir, asset);
      const dest = path.join(config.outputDir, asset);
      if (await fs.pathExists(source)) {
        await fs.copy(source, dest);
        console.log(`Copied ${asset} to dist/`);
      }
    }

    // Start PHP server and wait
    const serverProcess = await startPHPServer();
    await sleep(2000);

    // Convert pages and create configs
    await convertPages(config.pages);
    await createNetlifyRedirects();
    await createVercelConfig();

    // Cleanup
    if (serverProcess.pid) {
      process.kill(-serverProcess.pid);
    }

    console.log(
      "\nBuild complete! Your static site is ready in the dist/ directory."
    );
    console.log("\nTo deploy:");
    console.log("1. For Vercel: npm run deploy:vercel");
    console.log("2. For Netlify: npm run deploy:netlify");
  } catch (error) {
    console.error("Build failed:", error);
    process.exit(1);
  }
}

// Run build
build().catch(console.error);
