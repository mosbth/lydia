<?php if($content['created']): ?>
  <h1>Edit Content</h1>
  <p>You can edit and save this content.</p>
<?php else: ?>
  <h1>Create Content</h1>
  <p>Create new content.</p>
<?php endif; ?>


<?=$form->GetHTML(array('class'=>'content-edit'))?>

<p class='smaller-text'><em>
<?php if($content['created']): ?>
  This content were created by <?=$content['owner']?> <?=time_diff($content['created'])?> ago.
<?php else: ?>
  Content not yet created.
<?php endif; ?>

<?php if(isset($content['updated'])):?>
  Last updated <?=time_diff($content['updated'])?> ago.
<?php endif; ?>
</em></p>

<p>
<a href='<?=create_url('content', 'create')?>'>Create new</a>
<a href='<?=create_url('page', 'view', $content['id'])?>'>View</a>
<a href='<?=create_url('content')?>'>View all</a>
</p>

