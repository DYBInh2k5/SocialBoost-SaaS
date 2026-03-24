<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function index(Request $request)
    {
        $workspaces = $request->user()
            ->workspaces()
            ->with('owner')
            ->orderBy('name')
            ->get();

        $activeWorkspaceId = (int) $request->session()->get('current_workspace_id');

        if (! $activeWorkspaceId && $workspaces->isNotEmpty()) {
            $request->session()->put('current_workspace_id', $workspaces->first()->id);
            $activeWorkspaceId = $workspaces->first()->id;
        }

        return view('workspaces.index', [
            'workspaces' => $workspaces,
            'activeWorkspaceId' => $activeWorkspaceId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'workspace';
        $slug = $baseSlug;
        $suffix = 1;

        while (Workspace::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        $workspace = Workspace::create([
            'owner_id' => $request->user()->id,
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        $workspace->members()->attach($request->user()->id, ['role' => 'owner']);
        $request->session()->put('current_workspace_id', $workspace->id);

        return redirect()
            ->route('workspaces.index')
            ->with('status', 'Workspace created successfully.');
    }

    public function update(Request $request, Workspace $workspace)
    {
        abort_unless($workspace->owner_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
        ]);

        $workspace->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('workspaces.index')
            ->with('status', 'Workspace updated successfully.');
    }

    public function destroy(Request $request, Workspace $workspace)
    {
        abort_unless($workspace->owner_id === $request->user()->id, 403);

        $workspace->delete();

        $request->session()->forget('current_workspace_id');

        return redirect()
            ->route('workspaces.index')
            ->with('status', 'Workspace deleted successfully.');
    }

    public function switch(Request $request, Workspace $workspace)
    {
        $belongsToUser = $request->user()
            ->workspaces()
            ->where('workspaces.id', $workspace->id)
            ->exists();

        abort_unless($belongsToUser, 403);

        $request->session()->put('current_workspace_id', $workspace->id);

        return redirect()
            ->back()
            ->with('status', 'Switched workspace to '.$workspace->name.'.');
    }
}
