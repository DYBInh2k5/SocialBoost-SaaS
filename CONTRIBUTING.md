# Contributing

Cam on ban da quan tam dong gop cho SocialBoost SaaS.

## Quy trinh de xuat

1. Fork repository
2. Tao branch moi theo feature hoac fix
3. Commit ro rang theo nhom thay doi
4. Mo pull request vao nhanh main

## Quy uoc branch

- feat/<ten-tinh-nang>
- fix/<ten-loi>
- docs/<ten-tai-lieu>

## Quy uoc commit

Su dung Conventional Commits:
- feat: them tinh nang moi
- fix: sua loi
- docs: cap nhat tai lieu
- chore: cong viec bao tri
- refactor: cai tien cau truc code

Vi du:
- feat: add workspace switching
- fix: validate scheduled_for format

## Tieu chuan truoc khi tao PR

- Chay test pass:
  - php artisan test
- Dam bao migration chay duoc:
  - php artisan migrate
- Khong commit file nhay cam:
  - .env
  - credentials
  - api keys

## Checklist pull request

- Mo ta ngan gon muc tieu PR
- Liet ke file/module bi anh huong
- Neu co giao dien, dinh kem screenshot
- Xac nhan da test local

## Bao cao loi

Khi mo issue, vui long kem:
- Buoc tai hien
- Ket qua mong doi
- Ket qua thuc te
- Log loi neu co
- Moi truong (OS, PHP, Node)
