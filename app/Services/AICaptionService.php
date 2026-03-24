<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class AICaptionService
{
    public function generate(string $prompt, ?string $tone, ?string $platform): array
    {
        $apiKey = (string) config('services.openai.api_key');
        $model = (string) config('services.openai.model', 'gpt-4o-mini');

        if ($apiKey === '') {
            return $this->fallbackCaption($prompt, $tone, $platform);
        }

        $toneText = $tone ? "Tone: {$tone}." : '';
        $platformText = $platform ? "Platform: {$platform}." : '';

        $response = Http::withToken($apiKey)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You write concise and high-conversion social captions with clear hooks and CTAs.',
                    ],
                    [
                        'role' => 'user',
                        'content' => trim("{$toneText} {$platformText} Prompt: {$prompt}"),
                    ],
                ],
                'temperature' => 0.8,
                'max_tokens' => 220,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Failed to generate caption from AI provider.');
        }

        $caption = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($caption) || trim($caption) === '') {
            throw new RuntimeException('AI response did not contain a valid caption.');
        }

        return [
            'caption' => trim($caption),
            'model' => $model,
            'tokens_used' => data_get($response->json(), 'usage.total_tokens'),
        ];
    }

    private function fallbackCaption(string $prompt, ?string $tone, ?string $platform): array
    {
        $hook = $tone ? Str::headline($tone) : 'Fresh Idea';
        $platformTag = $platform ? " #{$platform}" : '';
        $summary = Str::of($prompt)->squish()->limit(140, '...');

        $caption = "{$hook}: {$summary}\n\nSave this for later, and share your take in the comments.{$platformTag}";

        return [
            'caption' => (string) $caption,
            'model' => 'local-fallback',
            'tokens_used' => null,
        ];
    }
}
