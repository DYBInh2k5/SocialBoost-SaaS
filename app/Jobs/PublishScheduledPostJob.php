<?php

namespace App\Jobs;

use App\Models\ScheduledPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishScheduledPostJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $scheduledPostId)
    {
    }

    public function handle(): void
    {
        $post = ScheduledPost::find($this->scheduledPostId);

        if (! $post || $post->status !== 'scheduled') {
            return;
        }

        $post->update([
            'status' => 'published',
            'published_at' => now(),
            'engagement_score' => random_int(20, 450),
        ]);
    }
}
