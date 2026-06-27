@php
    use App\Enums\IdeaStatus;
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

        <div>
            <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}"> All </a>
            @foreach (IdeaStatus::cases() as $status)
                <a
                    href="/ideas?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}"
                >
                    {{ $status->label() }}
                    <span class="pl-3 text-xs"> {{ $statusCounts->get($status->value) }} </span>
                </a>
            @endforeach
        </div>

        <div class="text-muted-foreground mt-10">
            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">
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

        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{
                    status: 'pending',
                    newLink: '',
                    links: [],
                }"
                method="POST"
                action="{{ route('idea.store') }}"
            >
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter an idea for your title"
                        autofocus
                        required
                    />

                    <div class="space-y-2">
                        <label for="status" class="label">Status</label>
                        <div class="flex gap-x-3">
                            @foreach (IdeaStatus::cases() as $status)
                                <button
                                    type="button"
                                    @click="status = @js($status->value)"
                                    data-test="button-status-{{ $status->value }}"
                                    class="btn btn-outlined h-10 flex-1"
                                    :class="{ 'btn-outlined': status !== @js($status->value) }"
                                >
                                    {{ $status->label() }}
                                </button>
                            @endforeach
                            <input type="hidden" name="status" :value="status" />
                        </div>
                        <x-form.error name="status" />
                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe you idea..."
                    />

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links" :key="link">
                                <div class="flex items-center gap-x-2">
                                    <input name="links[]" x-model="link" class="input" />
                                    <button
                                        type="button"
                                        aria-label="remove link"
                                        @click="links.splice(index, 1)"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close />
                                    </button>
                                </div>
                            </template>

                            <div class="flex items-center gap-x-2">
                                <input
                                    x-model="newLink"
                                    type="url"
                                    id="new-link"
                                    data-test="new-link"
                                    placeholder="http://example.com"
                                    autocomplete="url"
                                    class="input flex-1"
                                    spellcheck="false"
                                />

                                <button
                                    type="button"
                                    @click="
                                        links.push(newLink.trim());
                                        newLink = '';
                                    "
                                    data-test="submit-new-link-button"
                                    :disabled="newLink.trim().length === 0"
                                    aria-label="Add link button"
                                    class="form-muted-icon"
                                >
                                    <x-icons.close class="rotate-45" />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-5">
                        <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
