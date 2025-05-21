# Deploying to Vercel

Since Vercel doesn't support PHP natively, we'll need to convert the application to a static site or use a JAMstack approach.

## Option 1: Static Site Conversion

1. Create build script (build.js):

```javascript
const fs = require("fs");
const { execSync } = require("child_process");

// Start PHP server
const phpServer = execSync("php -S localhost:8000");

// Pages to convert
const pages = [
  "index.php",
  "konsultasi.php",
  "jurusan.php",
  "tentang.php",
  "hasiloutput.php",
];

// Create dist directory
if (!fs.existsSync("dist")) {
  fs.mkdirSync("dist");
}

// Copy assets
execSync("cp -r assets dist/");

// Convert PHP pages to static HTML
pages.forEach((page) => {
  const html = execSync(`curl http://localhost:8000/${page}`);
  const staticFile = page.replace(".php", ".html");
  fs.writeFileSync(`dist/${staticFile}`, html);
});

// Kill PHP server
phpServer.kill();
```

2. Create vercel.json:

```json
{
  "version": 2,
  "builds": [{ "src": "dist/**", "use": "@vercel/static" }],
  "routes": [{ "src": "/(.*)", "dest": "/dist/$1" }]
}
```

3. Update package.json:

```json
{
  "name": "spk-sekolah",
  "version": "1.0.0",
  "scripts": {
    "build": "node build.js"
  }
}
```

## Deployment Steps

1. Push your code to GitHub

2. Install Vercel CLI:

```bash
npm i -g vercel
```

3. Login to Vercel:

```bash
vercel login
```

4. Deploy:

```bash
vercel
```

Or use Vercel Dashboard:

- Go to vercel.com
- Import your GitHub repository
- Configure build settings:
  - Build Command: `npm run build`
  - Output Directory: `dist`

## Environment Variables

Set these in Vercel dashboard:

```
DB_HOST=your-database-host
DB_USER=your-database-user
DB_PASSWORD=your-database-password
DB_NAME=your-database-name
```

## Database Solutions

Use one of these database services:

1. PlanetScale (MySQL-compatible)
2. Supabase (PostgreSQL)
3. MongoDB Atlas
4. AWS RDS
