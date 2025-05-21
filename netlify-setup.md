# Deploying to Netlify

Similar to Vercel, Netlify doesn't support PHP directly. Here's how to deploy your application:

## Option 1: Static Site Conversion

1. Create netlify.toml in your project root:

```toml
[build]
  publish = "dist"
  command = "node build.js"

[[redirects]]
  from = "/*"
  to = "/index.html"
  status = 200
```

2. Create package.json if not exists:

```json
{
  "name": "spk-sekolah",
  "version": "1.0.0",
  "scripts": {
    "build": "node build.js"
  }
}
```

3. Use the same build.js script from Vercel setup to convert PHP to static HTML.

## Deployment Steps

### Option 1: Using Netlify CLI

1. Install Netlify CLI:

```bash
npm install -g netlify-cli
```

2. Login to Netlify:

```bash
netlify login
```

3. Initialize Netlify:

```bash
netlify init
```

4. Deploy:

```bash
netlify deploy --prod
```

### Option 2: Using Netlify Dashboard

1. Go to netlify.com
2. Sign up/Login
3. Click "New site from Git"
4. Choose your GitHub repository
5. Configure build settings:
   - Build command: `npm run build`
   - Publish directory: `dist`
6. Click "Deploy site"

## Environment Variables

Set these in Netlify dashboard (Site settings > Build & deploy > Environment):

```
DB_HOST=your-database-host
DB_USER=your-database-user
DB_PASSWORD=your-database-password
DB_NAME=your-database-name
```

## Continuous Deployment

Create GitHub Actions workflow:

```yaml
# .github/workflows/netlify.yml
name: Deploy to Netlify
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: actions/setup-node@v2
        with:
          node-version: "14"
      - run: npm install
      - run: npm run build
      - uses: netlify/actions/cli@master
        with:
          args: deploy --prod
        env:
          NETLIFY_AUTH_TOKEN: ${{ secrets.NETLIFY_AUTH_TOKEN }}
          NETLIFY_SITE_ID: ${{ secrets.NETLIFY_SITE_ID }}
```

## Post-Deployment Checks

1. Verify all pages load correctly
2. Check static assets (CSS, JS, images)
3. Test theme switching functionality
4. Verify forms and API endpoints
5. Test database connections
6. Check mobile responsiveness

## Troubleshooting Common Issues

1. Build Failures:

   - Check build logs in Netlify dashboard
   - Verify all dependencies are installed
   - Check file paths are correct

2. 404 Errors:

   - Verify redirects in netlify.toml
   - Check file names match routes
   - Ensure assets are in correct directories

3. Database Connection Issues:

   - Verify environment variables
   - Check database service is accessible
   - Confirm IP whitelist settings

4. Asset Loading:
   - Update relative paths
   - Check asset directory structure
   - Verify file permissions

## Monitoring

1. Enable Netlify Analytics
2. Set up error tracking (Sentry)
3. Configure status alerts
4. Monitor build minutes usage

## Security Considerations

1. Enable HTTPS (automatic with Netlify)
2. Set up proper headers:

```toml
[[headers]]
  for = "/*"
  [headers.values]
    X-Frame-Options = "DENY"
    X-XSS-Protection = "1; mode=block"
    X-Content-Type-Options = "nosniff"
```

3. Protect sensitive routes
4. Secure API endpoints
5. Use environment variables for secrets
