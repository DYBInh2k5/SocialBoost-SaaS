# Project Architecture

## Overview

SocialBoost la Laravel SaaS MVP cho social content operations.

Core goals:
- Multi-tenant workspace management
- Scheduled posting workflow
- Reusable template library
- AI caption generation
- Basic KPI dashboard

## Domain Model

1. users
- Tai khoan he thong

2. workspaces
- Don vi tenant
- owner_id tro den users

3. workspace_memberships
- Pivot users-workspaces
- role: owner, member

4. content_templates
- Template noi dung tai su dung theo workspace

5. scheduled_posts
- Bai dang theo lich
- status: draft, scheduled, published, failed
- published_at va engagement_score phuc vu dashboard

6. caption_generations
- Luu lich su AI generation
- prompt, tone, platform, model, tokens_used

## Request Flow

1. User login
2. User chon workspace active (session current_workspace_id)
3. Moi thao tac templates/posts/captions duoc scope theo workspace active
4. Dashboard tong hop so lieu theo workspace active

## AI Flow

1. CaptionGenerationController nhan prompt/tone/platform
2. AICaptionService goi OpenAI neu co api key
3. Neu khong co key thi fallback local caption
4. Ket qua duoc luu vao caption_generations

## Scheduling Flow

1. User tao post voi status scheduled va scheduled_for
2. Scheduler moi phut goi command posts:dispatch-scheduled
3. Command loc post den han va dispatch PublishScheduledPostJob
4. Job cap nhat post thanh published va set published_at

## Security and Boundaries

- Cac endpoint su dung auth middleware
- Workspace ownership check cho update/delete workspace
- Workspace scope check cho templates/posts
- Resolve workspace thong qua trait ResolvesCurrentWorkspace

## Current Limitations

- Chua ket noi API social media that su
- Chua co role permission chi tiet beyond owner/member
- Chua co billing plan

## Suggested Next Steps

1. Them role-based access control day du
2. Ket noi social APIs (Meta, TikTok, LinkedIn)
3. Them media upload + storage abstraction
4. Them audit log va rate limiting cho AI generation
5. Them feature tests cho luong workspace/templates/posts/captions
