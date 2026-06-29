@props ([
    'completed' => false,
    'type' => 'submit',
    'role' => 'checkbox',
])

<button
    type="{{ $type }}"
    role="{{ $role }}"
    aria-checked="{{ $completed ? 'true' : 'false' }}"
    {{ $attributes->class([
        'border-primary text-primary-foreground flex size-5 items-center justify-center rounded-lg border',
        'bg-primary' => $completed,
    ]) }}
>
    &check;
</button>
