#!/bin/bash

# Laravel Deployment Script for Shared Hosting
# This creates a clean build for production deployment

echo "🚀 Starting deployment build..."

# Build assets locally first
echo "🏗️ Building assets locally..."
npm run build

# Create deployment directory
DEPLOY_DIR="mpet-deploy-$(date +%Y%m%d-%H%M%S)"
mkdir $DEPLOY_DIR

echo "📁 Created deployment directory: $DEPLOY_DIR"

# Copy necessary files and directories
echo "📋 Copying application files..."

# Core Laravel files
cp -r app $DEPLOY_DIR/
cp -r bootstrap $DEPLOY_DIR/
cp -r config $DEPLOY_DIR/
cp -r database $DEPLOY_DIR/
cp -r lang $DEPLOY_DIR/
cp -r public $DEPLOY_DIR/
cp -r resources $DEPLOY_DIR/
cp -r routes $DEPLOY_DIR/
cp -r storage $DEPLOY_DIR/

# Root files (production essentials)
cp artisan $DEPLOY_DIR/
cp composer.json $DEPLOY_DIR/
cp composer.lock $DEPLOY_DIR/
cp README.md $DEPLOY_DIR/
cp .env.example $DEPLOY_DIR/

# No build configuration files needed since we pre-build locally
# Assets are already compiled in public/build/

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 $DEPLOY_DIR/storage
chmod -R 755 $DEPLOY_DIR/bootstrap/cache

# Create instructions file
cat > $DEPLOY_DIR/DEPLOYMENT_INSTRUCTIONS.txt << 'EOL'
DEPLOYMENT INSTRUCTIONS FOR SHARED HOSTING (cPanel)
==================================================

1. BEFORE UPLOADING:
   - Copy .env.example to .env: cp .env.example .env
   - Edit .env file with your actual database credentials and settings
   - Generate APP_KEY by running: php artisan key:generate (on server or locally)
   
2. UPLOAD FILES:
   - Upload ALL contents of this folder to your hosting account
   - If you have a public_html folder, upload everything there
   - Make sure .env file is uploaded (some FTP clients hide dot files)
   - Ensure public/build/ folder is uploaded (contains compiled assets)

3. AFTER UPLOADING:
   - Install dependencies: composer install --no-dev --optimize-autoloader
   - Run database migrations: php artisan migrate
   - Run seeders: php artisan db:seed
   - Clear caches: php artisan optimize

4. FOLDER PERMISSIONS:
   - storage/ should be writable (755 or 775)
   - bootstrap/cache/ should be writable (755 or 775)

5. WEB SERVER CONFIGURATION:
   - Document root should point to public/ folder
   - If you can't change document root, move public/* contents to your web root
     and update index.php paths accordingly

6. TESTING:
   - Visit your site URL
   - Try logging in with seeded users
   - Test the permission-based navigation

IMPORTANT NOTES:
- Assets are pre-compiled locally (no npm needed on server)
- Never upload vendor/ folder from development (use composer install on server)
- Never upload node_modules/ or .git/ folders
- Make sure .env is not accessible via web browser
- Always create .env from .env.example to avoid overwriting existing configuration

ENVIRONMENT VARIABLES TO CONFIGURE:
- APP_URL: Your production domain
- Database credentials (DB_*)
- ROOT_USER_PASSWORD: For creating seeded users
EOL

echo "✅ Deployment build completed!"
echo "📁 Deployment folder: $DEPLOY_DIR"
echo "📖 Read DEPLOYMENT_INSTRUCTIONS.txt in the deployment folder"
echo ""
echo "Next steps:"
echo "1. Upload the contents of $DEPLOY_DIR/ to your server"
echo "2. On server: cp .env.example .env"
echo "3. Edit .env with your production settings"
echo "4. Follow the remaining steps in DEPLOYMENT_INSTRUCTIONS.txt"