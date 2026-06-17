@props([
    'role' => null,
    'color' => null,
])

@php
    $roleStyles = [
        'admin' => 'bg-red text-white',
        'moderador' => 'bg-yellow text-ink',
        'usuario' => 'bg-green text-white',
    ];

    $colorStyles = [
        'green' => 'bg-[#e2f3eb] text-green-dark',
        'yellow' => 'bg-[#fff3cd] text-[#7a5800]',
        'red' => 'bg-[#fde9e6] text-red',
        'teal' => 'bg-[#dceef2] text-teal',
        'neutral' => 'bg-[#eef4f3] text-ink',
    ];

    if ($role !== null) {
        $tone = $roleStyles[$role] ?? $roleStyles['usuario'];
    } else {
        $tone = $colorStyles[$color] ?? $colorStyles['green'];
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center w-fit px-2.5 py-1 rounded-full text-[0.72rem] font-black uppercase tracking-wide $tone"]) }}>
    {{ $slot }}
</span>
