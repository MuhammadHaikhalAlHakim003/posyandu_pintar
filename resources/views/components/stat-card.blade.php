@props(['title', 'value', 'icon', 'color', 'trend' => null, 'valueId' => null])

<div class="stat-card">
    <div class="stat-icon {{ $color }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div>
        <p class="stat-value" @if($valueId) id="{{ $valueId }}" @endif>{{ $value }}</p>
        <p class="stat-title">{{ $title }}</p>
        @if($trend)
            <span class="stat-trend text-{{ $trend['value'] >= 0 ? 'success' : 'danger' }}">
                <i class="bi bi-arrow-{{ $trend['value'] >= 0 ? 'up' : 'down' }}"></i>
                {{ abs($trend['value']) }}% {{ $trend['label'] }}
            </span>
        @endif
    </div>
</div>
