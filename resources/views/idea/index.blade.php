@php
    use App\Models\Idea;
    use Illuminate\Support\Collection;

    /** @var Collection<int, Idea> $ideas */
    /** @var Collection<string, int> $statusCounts */
@endphp

<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground mt-2 text-sm">Capture your thoughts. Make a plan.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 h-32 w-full cursor-pointer text-left"
            >
                <p>What's the idea?</p>
            </x-card>
        </header>

        <x-idea.status-filter :counts="$statusCounts" />

        <div class="text-muted-foreground mt-10">
            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">
                        @if ($idea->image_path)
                            <div class="-mx-4 -mt-4 mb-4 overflow-hidden rounded-t-lg">
                                <img
                                    src="{{ asset('storage/' . $idea->image_path) }}"
                                    alt="idea image"
                                    class="h-48 w-full object-cover"
                                />
                            </div>
                        @endif
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time.</p>
                    </x-card>
                @endforelse
            </div>
        </div>

        <x-idea.modal />
    </div>
</x-layout>
