<?php use_stylesheet('component_css'); ?>
<?php use_javascript('component_js'); ?>

<div class="cacheableComponent_<?php echo $varParam ?? ''; ?>_<?php echo $componentParam ?? ''; ?>_<?php echo $requestParam ?? ''; ?>">OK</div>

<?php slot('component'); ?>Component<?php end_slot(); ?>
