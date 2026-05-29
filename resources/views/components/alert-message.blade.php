@props(['type' => 'info', 'message', 'dismissible' => true])

@php
$icons = [
    'success' => 'bi-check-circle-fill',
    'danger' => 'bi-x-circle-fill',
    'warning' => 'bi-exclamation-triangle-fill',
    'info' => 'bi-info-circle-fill'
];
@endphp

<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    <i class="bi {{ $icons[$type] ?? 'bi-info-circle-fill' }} me-2"></i>{{ $message }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
