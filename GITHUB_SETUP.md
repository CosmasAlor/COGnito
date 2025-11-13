# GitHub Repository Setup Guide

## Step 1: Install Git (if not already installed)

1. Download Git for Windows from: https://git-scm.com/download/win
2. Install Git with default settings
3. Restart your terminal/PowerShell after installation

## Step 2: Configure Git (First time only)

Open PowerShell or Git Bash and run:

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## Step 3: Initialize Git Repository (Already done)

The repository has been initialized. If you need to reinitialize:

```bash
git init
```

## Step 4: Add Files to Git

```bash
git add .
```

## Step 5: Create Initial Commit

```bash
git commit -m "Initial commit: COGnito project with Timesheet Management module"
```

## Step 6: Create GitHub Repository

1. Go to https://github.com and sign in (or create an account)
2. Click the "+" icon in the top right corner
3. Select "New repository"
4. Fill in the details:
   - **Repository name**: `COGnito` (or your preferred name)
   - **Description**: "Laravel-based business management system with Timesheet Management module"
   - **Visibility**: Choose Public or Private
   - **DO NOT** initialize with README, .gitignore, or license (we already have these)
5. Click "Create repository"

## Step 7: Connect Local Repository to GitHub

After creating the repository, GitHub will show you commands. Use these:

```bash
# Add the remote repository (replace YOUR_USERNAME with your GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/COGnito.git

# Rename the default branch to main (if needed)
git branch -M main

# Push your code to GitHub
git push -u origin main
```

## Step 8: Verify

Go to your GitHub repository page and verify that all files are uploaded.

## Alternative: Using GitHub CLI (if installed)

If you have GitHub CLI installed:

```bash
gh repo create COGnito --public --source=. --remote=origin --push
```

## Important Notes

- **Never commit sensitive files**: The `.env` file is already in `.gitignore` and won't be committed
- **Vendor folder**: The `vendor/` folder is also ignored (use `composer install` to restore)
- **Node modules**: The `node_modules/` folder is ignored (use `npm install` to restore)

## Future Updates

To push future changes:

```bash
git add .
git commit -m "Description of your changes"
git push
```

## Troubleshooting

### If Git is not recognized:
- Make sure Git is installed and added to your system PATH
- Restart your terminal after installing Git

### If you get authentication errors:
- Use GitHub Personal Access Token instead of password
- Or use SSH keys for authentication

### To check Git status:
```bash
git status
```

### To see what files will be committed:
```bash
git status
```

