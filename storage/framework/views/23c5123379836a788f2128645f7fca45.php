<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'subtitle', 'icon', 'value', 'name', 'checked' => false, 'color' => 'primary']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'subtitle', 'icon', 'value', 'name', 'checked' => false, 'color' => 'primary']); ?>
<?php foreach (array_filter((['title', 'subtitle', 'icon', 'value', 'name', 'checked' => false, 'color' => 'primary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<label class="kategori-card <?php echo e($checked ? 'selected' : ''); ?>">
    <input type="radio" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>" <?php echo e($checked ? 'checked' : ''); ?>>
    <div class="kategori-icon bg-<?php echo e($color); ?>">
        <i class="bi <?php echo e($icon); ?>"></i>
    </div>
    <div class="kategori-title"><?php echo e($title); ?></div>
    <div class="kategori-subtitle"><?php echo e($subtitle); ?></div>
</label>
<?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/components/kategori-card.blade.php ENDPATH**/ ?>