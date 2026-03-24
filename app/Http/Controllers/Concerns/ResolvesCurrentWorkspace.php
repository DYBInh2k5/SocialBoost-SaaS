<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Workspace;
use Illuminate\Http\Request;

trait ResolvesCurrentWorkspace
{
    protected function resolveWorkspace(Request $request): Workspace
    {
        $workspaceId = (int) $request->input('workspace_id', $request->session()->get('current_workspace_id'));

        $workspace = null;

        if ($workspaceId > 0) {
            $workspace = $request->user()
                ->workspaces()
                ->where('workspaces.id', $workspaceId)
                ->first();
        }

        $workspace ??= $request->user()->workspaces()->orderBy('workspaces.id')->first();

        abort_if(! $workspace, 403, 'Please create a workspace first.');

        $request->session()->put('current_workspace_id', $workspace->id);

        return $workspace;
    }
}
