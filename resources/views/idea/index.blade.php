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

        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{
                    status: 'pending',
                    newLink: '',
                    newStep: '',
                    links: [],
                    steps: [],

                    setStatus(value) {
                        this.status = value;
                    },

                    deleteLink(index) {
                        this.links.splice(index, 1);
                    },

                    pushLink() {
                        this.links.push(this.newLink.trim());
                        this.newLink = '';
                    },

                    deleteStep(index) {
                        this.steps.splice(index, 1);
                    },

                    pushStep() {
                        this.steps.push(this.newStep.trim());
                        this.newStep = '';
                    },

                    get isLinkEmpty() {
                        return this.newLink.trim().length === 0;
                    },

                    get isStepEmpty() {
                        return this.newStep.trim().length === 0;
                    },
                }"
                method="POST"
                action="{{ route('idea.store') }}"
                enctype="multipart/form-data"
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

                    <x-idea.status-selector selected="status" on-select="setStatus" />

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe you idea..."
                    />

                    <div class="space-y-2">
                        <label for="image" class="label"> Featured Image </label>

                        <input type="file" name="image" accept="image/*" />
                        <x-form.error name="image" />
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Actionable steps</legend>

                            <template x-for="(step, index) in steps" :key="index">
                                <div class="flex items-center gap-x-2">
                                    <input name="steps[]" x-model="steps[index]" class="input" />
                                    <button
                                        type="button"
                                        aria-label="remove link"
                                        @click="deleteStep(index)"
                                        class="form-muted-icon"
                                    >
                                        <x-icons.close />
                                    </button>
                                </div>
                            </template>

                            <div class="flex items-center gap-x-2">
                                <input
                                    x-model="newStep"
                                    id="new-step"
                                    data-test="new-step"
                                    placeholder="What needs to be done?"
                                    class="input flex-1"
                                    spellcheck="false"
                                />

                                <button
                                    type="button"
                                    @click="pushStep()"
                                    data-test="submit-new-step-button"
                                    :disabled="isStepEmpty"
                                    aria-label="Add link button"
                                    class="form-muted-icon"
                                >
                                    <x-icons.close class="rotate-45" />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links" :key="index">
                                <div class="flex items-center gap-x-2">
                                    <input name="links[]" x-model="links[index]" class="input" />
                                    <button
                                        type="button"
                                        aria-label="remove link"
                                        @click="deleteLink(index)"
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
                                    @click="pushLink()"
                                    data-test="submit-new-link-button"
                                    :disabled="isLinkEmpty"
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
