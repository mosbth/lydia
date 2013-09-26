<div class='box<?=isset($class) ? " {$class}" : null?>'>
  
<h4><?=htmlEnt($content['title'])?></h4>
<?=$content['data_filtered']?>

<?php if($user_is_admin || $content->CurrentUserIsOwner()): ?>
<footer class='ly-block-footer'>    
  <p class='ly-block-meta-footer'><a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a></p>
</footer>
<?php endif; ?>

</div>
