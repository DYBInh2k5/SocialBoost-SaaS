<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Workspaces</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded bg-green-100 p-4 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Create Workspace</h3>
                <form method="POST" action="{{ route('workspaces.store') }}" class="mt-4 flex gap-3">
                    @csrf
                    <x-text-input name="name" type="text" class="w-full" placeholder="Marketing Team" required />
                    <x-primary-button>Create</x-primary-button>
                </form>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Your Workspaces</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($workspaces as $workspace)
                        <div class="flex flex-col gap-3 rounded border border-gray-200 p-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $workspace->name }}</p>
                                <p class="text-sm text-gray-500">Owner: {{ $workspace->owner->name }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($activeWorkspaceId === $workspace->id)
                                    <span class="rounded bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">Active</span>
                                @else
                                    <form method="POST" action="{{ route('workspaces.switch', $workspace) }}">
                                        @csrf
                                        <x-secondary-button>Switch</x-secondary-button>
                                    </form>
                                @endif

                                @if (auth()->id() === $workspace->owner_id)
                                    <form method="POST" action="{{ route('workspaces.update', $workspace) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input name="name" value="{{ $workspace->name }}" class="rounded border-gray-300 text-sm" />
                                        <x-secondary-button>Rename</x-secondary-button>
                                    </form>

                                    <form method="POST" action="{{ route('workspaces.destroy', $workspace) }}" onsubmit="return confirm('Delete this workspace?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded border border-red-300 px-3 py-2 text-sm text-red-700">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No workspaces yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
