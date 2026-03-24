<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Social AI Dashboard') }}
            </h2>
            <span class="text-sm text-gray-500">
                Workspace: {{ $workspace?->name ?? 'None' }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            @if (! $workspace)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-6 text-amber-800">
                    You do not have a workspace yet. Create one in Workspaces to start.
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Scheduled</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['scheduled'] }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Published</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['published'] }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Captions Generated</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['captions_generated'] }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-5 shadow-sm">
                        <p class="text-sm text-gray-500">Avg Engagement</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $stats['avg_engagement'] }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Generate AI Caption</h3>
                        <form method="POST" action="{{ route('captions.generate') }}" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                            <div>
                                <x-input-label for="prompt" value="Content Prompt" />
                                <textarea id="prompt" name="prompt" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('prompt') }}</textarea>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="tone" value="Tone" />
                                    <x-text-input id="tone" name="tone" type="text" class="mt-1 block w-full" value="{{ old('tone') }}" placeholder="Friendly, premium, playful" />
                                </div>
                                <div>
                                    <x-input-label for="platform" value="Platform" />
                                    <x-text-input id="platform" name="platform" type="text" class="mt-1 block w-full" value="{{ old('platform') }}" placeholder="Instagram, TikTok, LinkedIn" />
                                </div>
                            </div>
                            <x-primary-button>{{ __('Generate Caption') }}</x-primary-button>
                        </form>

                        @error('caption_error')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if (session('generated_caption'))
                            <div class="mt-4 rounded-md border border-indigo-200 bg-indigo-50 p-4 text-sm text-indigo-900 whitespace-pre-line">{{ session('generated_caption') }}</div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-lg bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Scheduled Posts</h3>
                            <div class="mt-3 space-y-3">
                                @forelse ($recentPosts as $post)
                                    <div class="rounded border border-gray-200 p-3">
                                        <p class="text-sm font-medium text-gray-800">{{ $post->platform }} • {{ strtoupper($post->status) }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($post->post_text, 120) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $post->scheduled_for?->format('d/m/Y H:i') }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No scheduled posts yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="rounded-lg bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">Recent AI Captions</h3>
                            <div class="mt-3 space-y-3">
                                @forelse ($recentCaptions as $caption)
                                    <div class="rounded border border-gray-200 p-3">
                                        <p class="text-xs text-gray-500">{{ $caption->created_at->format('d/m/Y H:i') }} • {{ $caption->model }}</p>
                                        <p class="text-sm text-gray-700 mt-1">{{ \Illuminate\Support\Str::limit($caption->generated_caption, 120) }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No generated captions yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
