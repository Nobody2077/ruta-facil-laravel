@props([
    'padding' => 'p-5',
])

<div {{ $attributes->merge(['class' => "bg-surface border border-line rounded-lg shadow-[var(--shadow-rf)] $padding"]) }}>
    {{ $slot }}
</div>
