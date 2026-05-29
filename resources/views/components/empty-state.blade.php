@props(['icon' => 'bi-inbox', 'title' => 'Tidak ada data', 'description' => null, 'action' => null])

<div class="empty-state">
    <i class="bi {{ $icon }}"></i>
    <h5 class="mt-3">{{ $title }}</h5>
    @if($description)
        <p class="text-muted">{{ $description }}</p>
    @endif
    @if($action)
        {{ $action }}
    @endif
</div>
