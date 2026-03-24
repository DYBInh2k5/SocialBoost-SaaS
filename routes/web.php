<?php

use App\Http\Controllers\CaptionGenerationController;
use App\Http\Controllers\ContentTemplateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduledPostController;
use App\Http\Controllers\WorkspaceController;
use App\Models\CaptionGeneration;
use App\Models\ScheduledPost;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = request()->user();
        $workspace = $user->workspaces()
            ->where('workspaces.id', request()->session()->get('current_workspace_id'))
            ->first();

        $workspace ??= $user->workspaces()->orderBy('workspaces.id')->first();

        if ($workspace) {
            request()->session()->put('current_workspace_id', $workspace->id);
        }

        $stats = [
            'scheduled' => 0,
            'published' => 0,
            'captions_generated' => 0,
            'avg_engagement' => 0,
        ];

        $recentPosts = collect();
        $recentCaptions = collect();

        if ($workspace) {
            $stats['scheduled'] = ScheduledPost::where('workspace_id', $workspace->id)
                ->where('status', 'scheduled')
                ->count();
            $stats['published'] = ScheduledPost::where('workspace_id', $workspace->id)
                ->where('status', 'published')
                ->count();
            $stats['captions_generated'] = CaptionGeneration::where('workspace_id', $workspace->id)->count();
            $stats['avg_engagement'] = (int) round(
                ScheduledPost::where('workspace_id', $workspace->id)->avg('engagement_score') ?? 0
            );

            $recentPosts = ScheduledPost::where('workspace_id', $workspace->id)
                ->latest('scheduled_for')
                ->limit(5)
                ->get();

            $recentCaptions = CaptionGeneration::where('workspace_id', $workspace->id)
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('dashboard', [
            'workspace' => $workspace,
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'recentCaptions' => $recentCaptions,
        ]);
    })->name('dashboard');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces.index');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::patch('/workspaces/{workspace}', [WorkspaceController::class, 'update'])->name('workspaces.update');
    Route::delete('/workspaces/{workspace}', [WorkspaceController::class, 'destroy'])->name('workspaces.destroy');
    Route::post('/workspaces/{workspace}/switch', [WorkspaceController::class, 'switch'])->name('workspaces.switch');

    Route::get('/templates', [ContentTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [ContentTemplateController::class, 'store'])->name('templates.store');
    Route::patch('/templates/{contentTemplate}', [ContentTemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{contentTemplate}', [ContentTemplateController::class, 'destroy'])->name('templates.destroy');

    Route::get('/scheduled-posts', [ScheduledPostController::class, 'index'])->name('scheduled-posts.index');
    Route::post('/scheduled-posts', [ScheduledPostController::class, 'store'])->name('scheduled-posts.store');
    Route::patch('/scheduled-posts/{scheduledPost}', [ScheduledPostController::class, 'update'])->name('scheduled-posts.update');
    Route::delete('/scheduled-posts/{scheduledPost}', [ScheduledPostController::class, 'destroy'])->name('scheduled-posts.destroy');

    Route::post('/captions/generate', [CaptionGenerationController::class, 'store'])->name('captions.generate');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
