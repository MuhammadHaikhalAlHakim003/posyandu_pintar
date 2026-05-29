<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title', 'value', 'icon', 'color', 'trend' => null, 'valueId' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title', 'value', 'icon', 'color', 'trend' => null, 'valueId' => null]); ?>
<?php foreach (array_filter((['title', 'value', 'icon', 'color', 'trend' => null, 'valueId' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="stat-card">
    <div class="stat-icon <?php echo e($color); ?>">
        <i class="bi <?php echo e($icon); ?>"></i>
    </div>
    <div>
        <p class="stat-value" <?php if($valueId): ?> id="<?php echo e($valueId); ?>" <?php endif; ?>><?php echo e($value); ?></p>
        <p class="stat-title"><?php echo e($title); ?></p>
        <?php if($trend): ?>
            <span class="stat-trend text-<?php echo e($trend['value'] >= 0 ? 'success' : 'danger'); ?>">
                <i class="bi bi-arrow-<?php echo e($trend['value'] >= 0 ? 'up' : 'down'); ?>"></i>
                <?php echo e(abs($trend['value'])); ?>% <?php echo e($trend['label']); ?>

            </span>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/components/stat-card.blade.php ENDPATH**/ ?>