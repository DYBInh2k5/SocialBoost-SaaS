<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesCurrentWorkspace;
use App\Models\ContentTemplate;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;

class ScheduledPostController extends Controller
{
    use ResolvesCurrentWorkspace;

    public function index(Request $request)
    {
        $workspace = $this->resolveWorkspace($request);

        $posts = ScheduledPost::query()
            ->where('workspace_id', $workspace->id)
            ->latest('scheduled_for')
            ->paginate(10);

        $templates = ContentTemplate::query()
            ->where('workspace_id', $workspace->id)
            ->orderBy('title')
            ->get();

        return view('scheduled_posts.index', [
            'workspace' => $workspace,
            'posts' => $posts,
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {
        $workspace = $this->resolveWorkspace($request);

        $validated = $request->validate([
            'platform' => ['required', 'string', 'max:40'],
            'content_template_id' => ['nullable', 'integer'],
            'post_text' => ['required', 'string', 'max:5000'],
            'ai_caption' => ['nullable', 'string', 'max:2000'],
            'scheduled_for' => ['required', 'date'],
            'status' => ['required', 'in:draft,scheduled'],
        ]);

        $templateId = $validated['content_template_id'] ?? null;

        if ($templateId) {
            $templateExists = ContentTemplate::query()
                ->where('workspace_id', $workspace->id)
                ->where('id', $templateId)
                ->exists();

            abort_unless($templateExists, 422, 'Invalid template selection.');
        }

        ScheduledPost::create([
            'workspace_id' => $workspace->id,
            'user_id' => $request->user()->id,
            'content_template_id' => $templateId,
            'platform' => $validated['platform'],
            'post_text' => $validated['post_text'],
            'ai_caption' => $validated['ai_caption'] ?: null,
            'scheduled_for' => $validated['scheduled_for'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('scheduled-posts.index')
            ->with('status', 'Post scheduled successfully.');
    }

    public function update(Request $request, ScheduledPost $scheduledPost)
    {
        $workspace = $this->resolveWorkspace($request);
        abort_unless($scheduledPost->workspace_id === $workspace->id, 403);

        $validated = $request->validate([
            'platform' => ['required', 'string', 'max:40'],
            'post_text' => ['required', 'string', 'max:5000'],
            'ai_caption' => ['nullable', 'string', 'max:2000'],
            'scheduled_for' => ['required', 'date'],
            'status' => ['required', 'in:draft,scheduled,published,failed'],
        ]);

        $scheduledPost->update([
            'platform' => $validated['platform'],
            'post_text' => $validated['post_text'],
            'ai_caption' => $validated['ai_caption'] ?: null,
            'scheduled_for' => $validated['scheduled_for'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()
            ->route('scheduled-posts.index')
            ->with('status', 'Scheduled post updated.');
    }

    public function destroy(Request $request, ScheduledPost $scheduledPost)
    {
        $workspace = $this->resolveWorkspace($request);
        abort_unless($scheduledPost->workspace_id === $workspace->id, 403);

        $scheduledPost->delete();

        return redirect()
            ->route('scheduled-posts.index')
            ->with('status', 'Scheduled post deleted.');
    }
}
