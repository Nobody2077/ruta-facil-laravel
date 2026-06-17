@props([
    'variant' => 'default',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 min-h-[2.85rem] px-4 py-3 rounded-md font-extrabold no-underline text-center transition duration-150 hover:-translate-y-px disabled:opacity-60 disabled:pointer-events-none';

    $variants = [
        'primary' => 'bg-green text-white shadow-lg shadow-green/25 hover:bg-green-dark',
        'secondary' => 'bg-white/10 text-white border border-white/55 hover:bg-white/20',
        'danger' => 'bg-red text-white hover:brightness-95',
        'default' => 'bg-[#eef4f3] text-ink hover:bg-[#e0eae8]',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['default']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
