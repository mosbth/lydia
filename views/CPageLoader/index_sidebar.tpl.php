<?php if(isset($content)) : ?>
<div class='box'>
<h4><?=esc($content['title'])?></h4>
<?=$content['data_filtered']?>
<?php if($user_is_admin || $content->CurrentUserIsOwner()): ?>
<a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a>
<?php endif; ?>
</div>
<?php endif; ?>

