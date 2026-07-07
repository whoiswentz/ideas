@php
    use App\Models\Idea;
    use App\Models\Step;
@endphp

@props ([
    'idea' => new Idea()
])

@php
    /** @var Idea $idea */

    $name = $idea->exists ? 'edit-idea' : 'create-idea';
    $title = $idea->exists ? 'Edit Idea' : 'Create Idea';
    $submitButton = $idea->exists ? 'Update' : 'Create';
    $formAction = $idea->exists ? route('idea.update', $idea) : route('idea.store');

    $status = old('status', $idea->status->value);
    $links = old('links', $idea->links);
    $steps = old('steps', $idea->steps->map(fn(Step $step) => $step->description))
@endphp

<x-modal name="{{ $name }}" title="{{ $title }}">
    <form
        x-data="{
            status: @js($status),
            newLink: '',
            newStep: '',
            links: @js($links ?? []),
            steps: @js($steps),

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
        action="{{ $formAction }}"
        enctype="multipart/form-data"
    >
        @csrf

        @if ($idea->exists)
            @method ('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter an idea for your title"
                :value="$idea->title"
                autofocus
                required
            />

            <x-idea.status-selector selected="status" on-select="setStatus" />

            <x-form.field
                label="Description"
                name="description"
                :value="$idea->description"
                type="textarea"
                placeholder="Describe you idea..."
            />

            <div class="space-y-2">
                <label for="image" class="label"> Featured Image </label>

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img
                            src="{{ asset('storage/' . $idea->image_path) }}"
                            alt="idea image"
                            class="h-48 w-full rounded-lg object-cover"
                        />

                        <button class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                    </div>
                @endif

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
                <button type="submit" class="btn">{{ $submitButton }}</button>
            </div>
        </div>
    </form>

    @if ($idea->image_path)
        <form method="POST" action="{{ route('idea.image.destroy', $idea) }}" id="delete-image-form">
            @csrf
            @method ('DELETE')
        </form>
    @endif
</x-modal>
