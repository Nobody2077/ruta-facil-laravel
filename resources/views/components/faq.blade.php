@props(['question'])

<details {{ $attributes->merge(['class' => 'group rounded-lg border border-line bg-surface p-4 open:shadow-[var(--shadow-rf)]']) }}>
  <summary class="flex cursor-pointer list-none items-center justify-between gap-3 font-bold text-ink marker:hidden">
    <span>{{ $question }}</span>
    <span class="text-2xl leading-none text-green transition-transform group-open:rotate-45">+</span>
  </summary>
  <div class="mt-3 leading-relaxed text-muted">
    {{ $slot }}
  </div>
</details>
