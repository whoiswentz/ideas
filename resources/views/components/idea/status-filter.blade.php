@php
    use App\Enums\IdeaStatus;
@endphp

@props (['counts'])

<div>
    <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}"> All </a>
    @foreach (IdeaStatus::cases() as $status)
        <a
            href="/ideas?status={{ $status->value }}"
            class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}"
        >
            {{ $status->label() }}
            <span class="pl-3 text-xs"> {{ $counts->get($status->value) }} </span>
        </a>
    @endforeach
</div>
