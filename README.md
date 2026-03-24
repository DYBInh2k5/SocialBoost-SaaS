# SocialBoost SaaS

Nen tang SaaS quan ly noi dung AI cho social media, xay dung bang Laravel.

Muc tieu MVP:
- Luu tru va quan ly workspace theo mo hinh multi-tenant
- Len lich bai dang social
- Tai su dung content template
- Goi y caption bang AI
- Dashboard thong ke co ban

## Tinh nang chinh

1. Auth
- Dang ky, dang nhap, quan ly profile (Laravel Breeze)

2. Workspaces
- Tao workspace
- Chuyen workspace dang hoat dong
- Doi ten, xoa workspace (owner)

3. Content templates
- Tao template noi dung de tai su dung
- Cap nhat va xoa template theo workspace

4. Scheduled posts
- Tao bai dang draft hoac scheduled
- Chon template khi tao post
- Cap nhat trang thai draft, scheduled, published, failed

5. AI caption generation
- Generate caption theo prompt, tone, platform
- Luu lich su generate caption
- Co fallback local khi chua co OPENAI_API_KEY

6. Dashboard
- So bai dang da len lich
- So bai dang da publish
- So caption da generate
- Avg engagement score

## Kien truc nhanh

- Backend: Laravel 13
- Frontend: Blade + Tailwind
- Database: SQLite (mac dinh)
- Queue: database
- Scheduler: command chay moi phut de dispatch bai den lich

Xem them chi tiet tai file PROJECT_ARCHITECTURE.md.

## Cau truc module

- Workspaces: app/Http/Controllers/WorkspaceController.php
- Templates: app/Http/Controllers/ContentTemplateController.php
- Scheduled Posts: app/Http/Controllers/ScheduledPostController.php
- Captions: app/Http/Controllers/CaptionGenerationController.php
- AI service: app/Services/AICaptionService.php
- Publish job: app/Jobs/PublishScheduledPostJob.php
- Dispatch command: app/Console/Commands/DispatchScheduledPostsCommand.php

## Cai dat local

1. Cai dependencies
- composer install
- npm install

2. Tao env
- copy .env.example .env
- php artisan key:generate

3. Chay migration
- php artisan migrate

4. Build frontend
- npm run build

5. Chay app
- php artisan serve --host=127.0.0.1 --port=8000

## Cau hinh AI

Cap nhat .env:
- OPENAI_API_KEY=your_key_here
- OPENAI_MODEL=gpt-4o-mini

Neu de trong OPENAI_API_KEY, he thong se dung bo caption fallback local.

## Chay queue va scheduler

- php artisan queue:work
- php artisan schedule:work

Lenh scheduler se goi posts:dispatch-scheduled moi phut.

## Kiem thu

- php artisan test

## Trang thai hien tai

- Da hoan thanh MVP backend + giao dien co ban
- Da pass test suite mac dinh
- San sang de dua len GitHub

## Dua len GitHub

Xem huong dan tung buoc tai file GITHUB_RELEASE_CHECKLIST.md.
