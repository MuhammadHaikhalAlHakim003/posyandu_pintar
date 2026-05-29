@props(['title', 'subtitle', 'icon', 'value', 'name', 'checked' => false, 'color' => 'primary'])

<label class="kategori-card {{ $checked ? 'selected' : '' }}">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" {{ $checked ? 'checked' : '' }}>
    <div class="kategori-icon bg-{{ $color }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="kategori-title">{{ $title }}</div>
    <div class="kategori-subtitle">{{ $subtitle }}</div>
</label>
