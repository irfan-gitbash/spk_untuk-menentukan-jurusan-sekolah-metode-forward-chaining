# Deployment Guide for SPK Jurusan

## Prerequisites

1. A Vercel account (create one at vercel.com if you don't have it)
2. A MySQL database (you can use PlanetScale, Railway, or any other MySQL provider)
3. Git installed on your computer

## Setup Steps

### 1. Database Setup

1. Make sure your MySQL database is accessible from external connections
2. Note down the following database credentials:
   - Host URL
   - Database name
   - Username
   - Password
3. Import the database schema from `config/spk_jurusan.sql`

### 2. Prepare for Deployment

1. Rename `vercel.json.new` to `vercel.json`:

   ```bash
   mv vercel.json.new vercel.json
   ```

2. Make sure all your files are committed to Git:
   ```bash
   git add .
   git commit -m "Prepare for Vercel deployment"
   ```

### 3. Vercel Environment Variables

1. Go to your Vercel dashboard
2. Select your project
3. Go to Settings > Environment Variables
4. Add the following environment variables:
   - `DB_HOST` : Your database host URL
   - `DB_NAME` : Your database name
   - `DB_USER` : Your database username
   - `DB_PASSWORD` : Your database password

### 4. Deploy to Vercel

1. Install Vercel CLI:

   ```bash
   npm install -g vercel
   ```

2. Login to Vercel:

   ```bash
   vercel login
   ```

3. Deploy your project:

   ```bash
   vercel
   ```

4. Follow the prompts to link your project and deploy

### 5. Verify Deployment

1. Once deployed, Vercel will provide you with a URL
2. Visit the URL to ensure your site is working
3. Test the following:
   - Form submission on konsultasi.html
   - Data display on jurusan.html
   - Results display on hasiloutput.html

### Troubleshooting

If you encounter any issues:

1. Check Vercel deployment logs:

   - Go to your project on Vercel dashboard
   - Click on the latest deployment
   - Check the "Build" and "Runtime" logs

2. Database connection issues:

   - Verify environment variables are set correctly
   - Ensure database is accessible from Vercel's servers
   - Check if database credentials are correct

3. API errors:
   - Check browser console for error messages
   - Verify API routes in vercel.json are correct
   - Test API endpoints using Postman or similar tool

For additional help, refer to Vercel's documentation or contact support.
