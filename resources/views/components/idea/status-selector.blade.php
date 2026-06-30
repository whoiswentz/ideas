@php
    use App\Enums\IdeaStatus;
@endphp

@props (['name' => 'status', 'selected' => null, 'label' => 'Status', 'onSelect'])

@php
    /** @var IdeaStatus|string|null $selected */
    $selected = $selected instanceof IdeaStatus ? $selected->value : ($selected ?? IdeaStatus::PENDING->value);
@endphp

<div class="space-y-2">
    <label class="label">{{ $label }}</label>
    <div class="flex gap-x-3">
        @foreach (IdeaStatus::cases() as $case)
            <button
                type="button"
                @click="{{ $onSelect }}(@js($case->value))"
                data-test="button-status-{{ $case->value }}"
                class="btn h-10 flex-1"
                :class="{ 'btn-outlined': status !== @js($case->value) }"
            >
                {{ $case->label() }}
            </button>
        @endforeach
        <input type="hidden" name="{{ $name }}" value="{{ $selected }}" :value="status" />
    </div>
    <x-form.error :name="$name" />
</div>
