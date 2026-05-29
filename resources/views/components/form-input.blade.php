@props(['label', 'name', 'type' => 'text', 'placeholder' => null, 'value' => null, 'icon' => null, 'required' => false, 'help' => null])

<div class="mb-3">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        @if($icon)
            <span class="input-group-text"><i class="bi {{ $icon }}"></i></span>
        @endif
        <input type="{{ $type }}" name="{{ $name }}"
               class="form-control @error($name) is-invalid @enderror"
               placeholder="{{ $placeholder }}"
               value="{{ old($name, $value) }}"
               {{ $required ? 'required' : '' }}>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
