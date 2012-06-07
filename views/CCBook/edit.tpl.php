<?php if($book['created']): ?>
<h1><?=t('Edit book')?></h1>
<p><?=t('You can edit and save this book.')?></p>
<?php else: ?>
<h1><?=t('Create Book')?></h1>
<p><?=t('Create new book.')?></p>
<?php endif; ?>


<?=$form->GetHTML(array('class'=>''))?>

<p class='smaller-text'><em>
<?php if($book['created']): ?>
<?=t('This book were created by !owner !time ago.', array('!owner'=>$book['owner'], '!time'=>time_diff($book['created'])))?>
<?php else: ?>
Book not yet created.
<?php endif; ?>

<?php if(isset($book['updated'])):?>
Last updated <?=time_diff($book['updated'])?> ago.
<?php endif; ?>
</em></p>

<p>
<a href='<?=create_url('book/create')?>'>Create new</a>
<a href='<?=create_url('book', 'view', $book['id'])?>'>View</a>
<a href='<?=create_url('book')?>'>View all</a>
</p>

