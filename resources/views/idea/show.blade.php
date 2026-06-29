@php
    use App\Models\Idea

    /** @var Idea $idea */
@endphp

<x-layout>
    <div class="mx-auto max-w-4xl py-8">
        <div class="flex justify-between">
            <a href="{{ route('idea.index') }}" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back /> Back to Ideas
            </a>
            <div class="flex items-center gap-x-3">
                <button class="btn btn-outlined">Edit Idea</button>
                <form method="POST" action="{{ route('idea.destroy', $idea) }}">
                    @csrf
                    @method ('DELETE')

                    <button class="btn btn-outlined text-red-500">Delete Idea</button>
                </form>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            <h1 class="text-4xl font-bold">{{ $idea->title }}</h1>
            <div class="mt-2 flex items-center gap-x-3">
                <x-idea.status-label :status="$idea->status->value"> {{ $idea->status->label() }} </x-idea.status-label>
                <div class="text-muted-foreground text-sm">{{ $idea->created_at->diffForHumans() }}</div>
            </div>
            <x-card class="mt-6">
                <div class="text-foreground max-w-none cursor-pointer">{{ $idea->description }}</div>
            </x-card>

            @if ($idea->steps->count())
                <div>
                    <h3 class="mt-6 text-xl font-bold">Actionable steps</h3>
                    <div class="mt-3 space-y-2">
                        @foreach ($idea->steps as $step)
                            <form method="POST" action="{{ route('step.update', $step) }}">
                                @csrf
                                @method ('PATCH')

                                <x-card class="text-primary flex items-center gap-3 font-medium">
                                    <div class="flex items-center gap-x-3">
                                        <x-idea.step-check :completed="$step->completed" />
                                        <span
                                            class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}"
                                            >{{ $step->description }}</span
                                        >
                                    </div>
                                </x-card>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($idea->links->count())
                <div>
                    <h3 class="mt-6 text-xl font-bold">Links</h3>
                    <div class="mt-3 space-y-2">
                        @foreach ($idea->links as $link)
                            <x-card :href="$link" class="text-primary flex items-center gap-3 font-medium">
                                <x-icons.external /> {{ $link }}
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
