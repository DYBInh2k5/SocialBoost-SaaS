<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesCurrentWorkspace;
use App\Models\CaptionGeneration;
use App\Services\AICaptionService;
use Illuminate\Http\Request;
use RuntimeException;

class CaptionGenerationController extends Controller
{
    use ResolvesCurrentWorkspace;

    public function store(Request $request, AICaptionService $aiCaptionService)
    {
        $workspace = $this->resolveWorkspace($request);

        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
            'tone' => ['nullable', 'string', 'max:80'],
            'platform' => ['nullable', 'string', 'max:40'],
        ]);

        try {
            $result = $aiCaptionService->generate(
                $validated['prompt'],
                $validated['tone'] ?? null,
                $validated['platform'] ?? null,
            );
        } catch (RuntimeException $exception) {
            return redirect()
                ->back()
                ->withErrors(['caption_error' => $exception->getMessage()])
                ->withInput();
        }

        CaptionGeneration::create([
            'workspace_id' => $workspace->id,
            'user_id' => $request->user()->id,
            'prompt' => $validated['prompt'],
            'tone' => $validated['tone'] ?? null,
            'platform' => $validated['platform'] ?? null,
            'generated_caption' => $result['caption'],
            'model' => $result['model'],
            'tokens_used' => $result['tokens_used'],
        ]);

        return redirect()
            ->back()
            ->with('generated_caption', $result['caption'])
            ->with('status', 'Caption generated successfully.');
    }
}
