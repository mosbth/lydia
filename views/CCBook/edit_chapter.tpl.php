<?php if($action == 'edit'): ?>
<h1><?=t('Edit book chapter')?></h1>
<p><?=t('You can edit and save this book chapter.')?></p>
<?php else: ?>
<h1><?=t('Create book chapter')?></h1>
<p><?=t('Create new book chapter.')?></p>
<?php endif; ?>


<?=$form->GetHTML(array('class'=>''))?>


<p>
<a href='<?=create_url('book', 'view', $book['id'])?>'>View book</a>
<a href='<?=create_url('book', 'chapter/add', $book['id'])?>'>Add chapter</a>
</p>
<p>
<a href='<?=create_url('book')?>'>View all books</a>
</p>

