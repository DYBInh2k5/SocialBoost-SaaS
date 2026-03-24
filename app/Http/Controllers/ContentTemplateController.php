<?php

namespace App\Http\Controllers;

use App\Models\ContentTemplate;
use App\Http\Controllers\Concerns\ResolvesCurrentWorkspace;
use Illuminate\Http\Request;

class ContentTemplateController extends Controller
{
    use ResolvesCurrentWorkspace;

    public function index(Request $request)
    {
        $workspace = $this->resolveWorkspace($request);

        $templates = ContentTemplate::query()
            ->where('workspace_id', $workspace->id)
            ->latest()
            ->paginate(10);

        return view('content_templates.index', [
            'workspace' => $workspace,
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {
        $workspace = $this->resolveWorkspace($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'platform' => ['nullable', 'string', 'max:40'],
            'base_text' => ['required', 'string', 'max:5000'],
        ]);

        ContentTemplate::create([
            'workspace_id' => $workspace->id,
            'title' => $validated['title'],
            'platform' => $validated['platform'] ?: null,
            'base_text' => $validated['base_text'],
        ]);

        return redirect()
            ->route('templates.index')
            ->with('status', 'Template saved.');
    }

    public function update(Request $request, ContentTemplate $contentTemplate)
    {
        $workspace = $this->resolveWorkspace($request);
        abort_unless($contentTemplate->workspace_id === $workspace->id, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'platform' => ['nullable', 'string', 'max:40'],
            'base_text' => ['required', 'string', 'max:5000'],
        ]);

        $contentTemplate->update([
            'title' => $validated['title'],
            'platform' => $validated['platform'] ?: null,
            'base_text' => $validated['base_text'],
        ]);

        return redirect()
            ->route('templates.index')
            ->with('status', 'Template updated.');
    }

    public function destroy(Request $request, ContentTemplate $contentTemplate)
    {
        $workspace = $this->resolveWorkspace($request);
        abort_unless($contentTemplate->workspace_id === $workspace->id, 403);

        $contentTemplate->delete();

        return redirect()
            ->route('templates.index')
            ->with('status', 'Template deleted.');
    }
}
