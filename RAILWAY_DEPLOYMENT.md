# Railway Deployment Guide for Biblio Symfony

## 🚀 Complete Railway Deployment Guide

This guide will help you deploy the Biblio Library Management System to Railway.

---

## Prerequisites

1. **GitHub Account** - Your code should be pushed to GitHub
2. **Railway Account** - Sign up at [railway.app](https://railway.app)
3. **Stripe Account** (optional) - For payment features

---

## Step 1: Prepare Your Repository

The following files have been created for Railway deployment:
- `Procfile` - Process definition
- `nixpacks.toml` - Build configuration
- `railway.json` - Railway-specific settings

Push these to GitHub:
```bash
git add Procfile nixpacks.toml railway.json .env.railway.example
git commit -m "Add Railway deployment configuration"
git push origin main
```

---

## Step 2: Create Railway Project

1. Go to [railway.app](https://railway.app) and log in
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Choose `ahmedKhlif/biblio_Symfony`
5. Click **"Deploy Now"**

---

## Step 3: Add MySQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"Add MySQL"**
3. Railway will automatically create a MySQL instance
4. Click on the MySQL service to see connection details

---

## Step 4: Configure Environment Variables

In Railway Dashboard, go to your **web service** → **Variables** tab.

Add these variables:

| Variable | Value |
|----------|-------|
| `APP_ENV` | `prod` |
| `APP_DEBUG` | `0` |
| `APP_SECRET` | Generate with: `openssl rand -hex 16` |
| `DATABASE_URL` | `${{MySQL.DATABASE_URL}}` (Railway reference) |
| `MAILER_DSN` | Your SMTP configuration |
| `STRIPE_PUBLISHABLE_KEY` | Your Stripe public key |
| `STRIPE_SECRET_KEY` | Your Stripe secret key |
| `DEFAULT_URI` | `https://${{RAILWAY_PUBLIC_DOMAIN}}` |

### How to link MySQL:
1. Click **"+ New Variable"**
2. For `DATABASE_URL`, click the **"Add Reference"** button
3. Select **MySQL** → **DATABASE_URL**
4. This creates: `${{MySQL.DATABASE_URL}}`

---

## Step 5: Run Database Migrations

### Option A: Using Railway CLI

1. Install Railway CLI:
```bash
npm install -g @railway/cli
```

2. Login and link project:
```bash
railway login
railway link
```

3. Run migrations:
```bash
railway run php bin/console doctrine:migrations:migrate --no-interaction
```

### Option B: Using Railway Shell

1. In Railway Dashboard, click on your web service
2. Go to **"Settings"** tab
3. Scroll to **"Service Commands"**
4. Add a one-time command:
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

---

## Step 6: Configure Domain

1. In Railway, click on your web service
2. Go to **"Settings"** tab
3. Under **"Networking"** → **"Public Networking"**
4. Click **"Generate Domain"**
5. You'll get a URL like: `your-app-production.up.railway.app`

### Custom Domain (Optional):
1. Click **"+ Custom Domain"**
2. Enter your domain (e.g., `biblio.yourdomain.com`)
3. Add the CNAME record to your DNS provider

---

## Step 7: Verify Deployment

1. Visit your Railway URL
2. Check the logs in Railway Dashboard for any errors
3. Test key features:
   - [ ] Homepage loads
   - [ ] Login/Registration works
   - [ ] Database queries work (list books)
   - [ ] Admin panel accessible

---

## Environment Variables Reference

### Required Variables

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=<generate-random-32-char-string>
DATABASE_URL=${{MySQL.DATABASE_URL}}
```

### Optional Variables

```env
# Email (required for registration/password reset)
MAILER_DSN=smtp://user:pass@smtp.gmail.com:587

# Stripe (required for payments)
STRIPE_PUBLISHABLE_KEY=pk_live_xxx
STRIPE_SECRET_KEY=sk_live_xxx

# App URL
DEFAULT_URI=https://your-app.railway.app
```

---

## Troubleshooting

### Issue: "Class not found" errors
**Solution:** Clear cache after deployment
```bash
railway run php bin/console cache:clear --env=prod
```

### Issue: Database connection failed
**Solution:** Verify DATABASE_URL format
- Must start with `mysql://`
- Check if MySQL service is running in Railway

### Issue: Assets not loading (CSS/JS broken)
**Solution:** Run asset install
```bash
railway run php bin/console assets:install --env=prod
```

### Issue: 500 Internal Server Error
**Solution:** Check logs and ensure APP_SECRET is set
1. Go to Railway Dashboard → Your Service → Logs
2. Look for the actual error message
3. Common fix: Set a valid `APP_SECRET`

### Issue: File uploads not persisting
**Note:** Railway has ephemeral filesystem. For persistent uploads:
1. Use a cloud storage service (AWS S3, Cloudinary)
2. Or add a Railway Volume

### Issue: Migrations fail
**Solution:** Run manually
```bash
railway run php bin/console doctrine:schema:update --force
```

---

## Production Checklist

- [ ] `APP_ENV=prod` is set
- [ ] `APP_DEBUG=0` is set
- [ ] `APP_SECRET` is a secure random string
- [ ] `DATABASE_URL` references Railway MySQL
- [ ] Domain is configured
- [ ] SSL is enabled (automatic with Railway)
- [ ] Migrations have been run
- [ ] Test user registration and login
- [ ] Test admin panel access

---

## Monitoring & Maintenance

### View Logs
- Railway Dashboard → Your Service → **Logs** tab

### Restart Service
- Railway Dashboard → Your Service → **Settings** → **Restart**

### Scale Service
- Railway Dashboard → Your Service → **Settings** → **Scaling**

### Database Backups
- Click MySQL service → **Data** tab → **Backups**

---

## Estimated Costs

Railway offers:
- **Free Tier**: $5 credit/month (enough for small projects)
- **Hobby Plan**: $5/month
- **Pro Plan**: $20/month

MySQL database uses separate resources.

---

## Support

- Railway Docs: https://docs.railway.app
- Symfony Docs: https://symfony.com/doc
- Project Issues: https://github.com/ahmedKhlif/biblio_Symfony/issues
