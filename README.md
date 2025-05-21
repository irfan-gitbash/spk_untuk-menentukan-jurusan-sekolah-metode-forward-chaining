# SPK Sekolah - Deployment Guide

This guide will help you deploy your PHP application to Vercel or Netlify.

## Quick Start

1. Install Node.js if you haven't already:

   - Download from [nodejs.org](https://nodejs.org)

2. Install dependencies:

```bash
npm install
```

3. Build the static site:

```bash
npm run build
```

4. Choose your deployment platform:

### Deploy to Vercel

```bash
npm run deploy:vercel
```

### Deploy to Netlify

```bash
npm run deploy:netlify
```

## Detailed Instructions

- For Vercel deployment: See [vercel-setup.md](vercel-setup.md)
- For Netlify deployment: See [netlify-setup.md](netlify-setup.md)

## Important Notes

1. Database Configuration

   - Your PHP application uses MySQL
   - For static deployment, consider using:
     - PlanetScale (MySQL compatible)
     - Supabase (PostgreSQL)
     - MongoDB Atlas
     - AWS RDS

2. File Structure After Build

```
dist/
├── assets/
│   ├── style.css
│   └── js/
├── index.html        (converted from index.php)
├── konsultasi.html   (converted from konsultasi.php)
├── jurusan.html      (converted from jurusan.php)
├── tentang.html      (converted from tentang.php)
└── hasiloutput.html  (converted from hasiloutput.php)
```

3. Environment Variables
   Make sure to set these in your deployment platform:
   ```
   DB_HOST=your-database-host
   DB_USER=your-database-user
   DB_PASSWORD=your-database-password
   DB_NAME=your-database-name
   ```

## Troubleshooting

1. Build Issues

   - Check if PHP server is running
   - Verify all paths in build.js
   - Check Node.js version (use 14+)

2. Deployment Issues

   - Verify platform configuration
   - Check environment variables
   - Review build logs

3. Runtime Issues
   - Check browser console for errors
   - Verify asset paths
   - Test database connectivity

## Support

For detailed deployment instructions:

- Vercel: [vercel-setup.md](vercel-setup.md)
- Netlify: [netlify-setup.md](netlify-setup.md)

## License

This project is licensed under the MIT License - see the LICENSE file for details.
