<?php if ($sf_user->hasFlash('notice')): ?>
  <p class="sf_planet_notice"><?php echo $sf_user->getFlash('notice') ?></p>
<?php endif; ?>

<?php if ($sf_user->hasFlash('warning')): ?>
  <p class="sf_planet_warning"><?php echo $sf_user->getFlash('warning') ?></p>
<?php endif; ?>