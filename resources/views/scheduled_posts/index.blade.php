<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Scheduled Posts</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded bg-green-100 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Schedule New Post ({{ $workspace->name }})</h3>
                <form method="POST" action="{{ route('scheduled-posts.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">

                    <div class="grid gap-3 md:grid-cols-3">
                        <x-text-input name="platform" type="text" placeholder="Instagram" required />
                        <select name="content_template_id" class="rounded-md border-gray-300">
                            <option value="">No template</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->title }}</option>
                            @endforeach
                        </select>
                        <input name="scheduled_for" type="datetime-local" class="rounded-md border-gray-300" required />
                    </div>

                    <textarea name="post_text" rows="4" class="w-full rounded-md border-gray-300" placeholder="Main post content" required>{{ old('post_text') }}</textarea>
                    <textarea name="ai_caption" rows="3" class="w-full rounded-md border-gray-300" placeholder="Optional AI caption">{{ old('ai_caption') }}</textarea>

                    <select name="status" class="rounded-md border-gray-300">
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                    </select>

                    <x-primary-button>Create Scheduled Post</x-primary-button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Post Queue</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($posts as $post)
                        <div class="rounded border border-gray-200 p-4">
                            <form method="POST" action="{{ route('scheduled-posts.update', $post) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">

                                <div class="grid gap-2 md:grid-cols-3">
                                    <input name="platform" value="{{ $post->platform }}" class="rounded border-gray-300 text-sm" required />
                                    <input name="scheduled_for" type="datetime-local" value="{{ optional($post->scheduled_for)->format('Y-m-d\\TH:i') }}" class="rounded border-gray-300 text-sm" required />
                                    <select name="status" class="rounded border-gray-300 text-sm">
                                        @foreach (['draft', 'scheduled', 'published', 'failed'] as $status)
                                            <option value="{{ $status }}" @selected($post->status === $status)>{{ strtoupper($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <textarea name="post_text" rows="3" class="w-full rounded border-gray-300 text-sm" required>{{ $post->post_text }}</textarea>
                                <textarea name="ai_caption" rows="2" class="w-full rounded border-gray-300 text-sm">{{ $post->ai_caption }}</textarea>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button class="rounded border border-gray-300 px-3 py-2 text-sm">Update</button>
                                    <span class="text-xs text-gray-500">Engagement: {{ $post->engagement_score }}</span>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('scheduled-posts.destroy', $post) }}" class="mt-2" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded border border-red-300 px-3 py-2 text-sm text-red-700">Delete</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No posts yet.</p>
                    @endforelse
                </div>
                <div class="mt-4">{{ $posts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
