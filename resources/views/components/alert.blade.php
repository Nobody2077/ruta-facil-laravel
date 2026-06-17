@props([
    'type' => 'success',
])

@php
    $styles = [
        'success' => 'bg-[#dff1e9] text-green-dark',
        'error' => 'bg-[#fde9e6] text-red',
        'warning' => 'bg-[#fff3cd] text-[#7a5800]',
        'info' => 'bg-[#dceef2] text-teal',
    ];

    $tone = $styles[$type] ?? $styles['success'];
@endphp

<div role="alert" {{ $attributes->merge(['class' => "block px-4 py-3 rounded-md font-extrabold $tone"]) }}>
    {{ $slot }}
</div>
