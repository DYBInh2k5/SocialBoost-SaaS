# GitHub Release Checklist

## 1) Kiem tra truoc khi push

- Dam bao file .env khong bi track
- Dam bao node_modules va vendor khong bi track
- Chay test:
  - php artisan test
- Chay migration local:
  - php artisan migrate

## 2) Khoi tao git (neu chua co)

- git init
- git branch -M main

## 3) Tao commit dau tien

- git add .
- git commit -m "feat: initial SocialBoost SaaS MVP"

## 4) Tao repository tren GitHub

- Tao repo moi, vi du socialboost-saas
- Khong can tao README tren GitHub (vi da co local)

## 5) Gan remote va push

- git remote add origin https://github.com/<your-username>/socialboost-saas.git
- git push -u origin main

## 6) Sau khi push

- Them About va topics cho repo
- Bat branch protection cho main
- Bat Issues + Projects neu can

## 7) Optional CI can them

- Tao workflow chay php artisan test khi co pull request
- Them badge CI vao README

## 8) Optional docs can bo sung

- CHANGELOG.md
- CONTRIBUTING.md
- LICENSE
