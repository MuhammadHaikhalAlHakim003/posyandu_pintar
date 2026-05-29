@props(['size' => 'md', 'text' => null])

@php
$sizeMap = [
    'sm' => '1rem',
    'md' => '2rem',
    'lg' => '3rem'
];
$dimension = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div class="text-center py-4">
    <div class="spinner-border text-primary" style="width: {{ $dimension }}; height: {{ $dimension }};" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    @if($text)
        <p class="text-muted mt-2 mb-0">{{ $text }}</p>
    @endif
</div>
