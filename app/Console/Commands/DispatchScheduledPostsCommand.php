<?php

namespace App\Console\Commands;

use App\Jobs\PublishScheduledPostJob;
use App\Models\ScheduledPost;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('posts:dispatch-scheduled')]
#[Description('Dispatch due scheduled posts to the queue')]
class DispatchScheduledPostsCommand extends Command
{
    public function handle(): int
    {
        $posts = ScheduledPost::query()
            ->where('status', 'scheduled')
            ->where('scheduled_for', '<=', now())
            ->orderBy('scheduled_for')
            ->limit(100)
            ->get(['id']);

        foreach ($posts as $post) {
            PublishScheduledPostJob::dispatch($post->id);
        }

        $this->info("Dispatched {$posts->count()} scheduled post job(s).");

        return self::SUCCESS;
    }
}
