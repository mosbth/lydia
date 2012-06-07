<h1><?=t('Books')?></h1>
<p><?=t('Create, edit and view books.')?></p>

<h2><?=('All books')?></h2>

<?php if(!isset($books)):?>
<p>No content exists.</p>
<?php else:?>
<ul>
  <?php foreach($books as $val):?>
  <li><?=$val['id']?>, <?=esc($val['title'])?> by <?=$val['owner']?> <a href='<?=create_url("book/edit/{$val['id']}")?>'>edit</a> <a href='<?=create_url("book/view/{$val['id']}")?>'>view</a>
  <?php endforeach; ?>
</ul>
<?php endif;?>

<h2>Actions</h2>
<ul>
  <li><a href='<?=create_url('book/create')?>'>Create new book</a>
</ul>
