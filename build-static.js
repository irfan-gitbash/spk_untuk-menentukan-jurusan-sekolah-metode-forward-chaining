const fs = require("fs-extra");
const { execSync } = require("child_process");
const path = require("path");

// Basic configuration
const config = {
  phpPath: "C:\\xampp\\php\\php.exe",
  port: 8000,
  pages: [
    "index.php",
    "konsultasi.php",
    "jurusan.php",
    "tentang.php",
    "hasiloutput.php",
  ],
};

// Create dist directory
console.log("Creating dist directory...");
fs.emptyDirSync("dist");

// Copy assets
console.log("Copying assets...");
fs.copySync("assets", "dist/assets");

// Start PHP server
console.log("Starting PHP server...");
try {
  const server = execSync(`"${config.phpPath}" -S localhost:${config.port}`, {
    stdio: "pipe",
    detached: true,
  });

  // Wait for server to start
  setTimeout(() => {
    // Convert PHP pages to static HTML
    config.pages.forEach((page) => {
      try {
        console.log(`Converting ${page}...`);
        const html = execSync(`curl http://localhost:${config.port}/${page}`);
        fs.writeFileSync(`dist/${page.replace(".php", ".html")}`, html);
      } catch (error) {
        console.error(`Error converting ${page}:`, error.message);
      }
    });

    // Create platform configs
    fs.writeFileSync(
      "dist/_redirects",
      config.pages
        .map((page) => `/${page} /${page.replace(".php", ".html")} 200`)
        .join("\n")
    );

    fs.writeFileSync(
      "vercel.json",
      JSON.stringify(
        {
          version: 2,
          builds: [{ src: "dist/**", use: "@vercel/static" }],
          routes: [{ src: "/(.*)", dest: "/dist/$1" }],
        },
        null,
        2
      )
    );

    // Kill PHP server
    if (server.pid) {
      process.kill(-server.pid);
    }

    console.log("Build complete! Files are in the dist/ directory");
  }, 2000);
} catch (error) {
  console.error("Build failed:", error.message);
  process.exit(1);
}
