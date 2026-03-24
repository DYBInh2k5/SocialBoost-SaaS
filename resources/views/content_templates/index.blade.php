<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Content Templates</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded bg-green-100 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Create Template for {{ $workspace->name }}</h3>
                <form method="POST" action="{{ route('templates.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                    <div class="grid gap-3 md:grid-cols-2">
                        <x-text-input name="title" type="text" placeholder="Promo launch" required />
                        <x-text-input name="platform" type="text" placeholder="Instagram" />
                    </div>
                    <textarea name="base_text" rows="4" class="w-full rounded-md border-gray-300" placeholder="Core message to reuse" required></textarea>
                    <x-primary-button>Save Template</x-primary-button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Template Library</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($templates as $template)
                        <div class="rounded border border-gray-200 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-medium text-gray-900">{{ $template->title }} @if($template->platform)• {{ $template->platform }}@endif</p>
                                <p class="text-xs text-gray-500">{{ $template->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">{{ $template->base_text }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('templates.update', $template) }}" class="flex flex-wrap gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                                    <input name="title" value="{{ $template->title }}" class="rounded border-gray-300 text-sm" required />
                                    <input name="platform" value="{{ $template->platform }}" class="rounded border-gray-300 text-sm" />
                                    <input name="base_text" value="{{ $template->base_text }}" class="rounded border-gray-300 text-sm min-w-80" required />
                                    <button class="rounded border border-gray-300 px-3 py-2 text-sm">Update</button>
                                </form>
                                <form method="POST" action="{{ route('templates.destroy', $template) }}" onsubmit="return confirm('Delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded border border-red-300 px-3 py-2 text-sm text-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No templates yet.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $templates->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
