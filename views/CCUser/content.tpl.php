<?php include($header); ?>

<h2><?=t('Content')?></h2>

<p>list all content in a table, enable sorting, searching, create new content, edit existing. Wsatebasket.</p>

<h2>All content</h2>
<?php if($content != null):?>
  <ul>
  <?php foreach($content as $val):?>
    <li><?=$val['id']?>, <?=esc($val['title'])?> by <?=$val['owner']?> <a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a> <a href='<?=create_url("page/view/{$val['id']}")?>'>view</a>
  <?php endforeach; ?>
  </ul>
<?php else:?>
  <p>No content exists.</p>
<?php endif;?>

<h2>Actions</h2>
<ul>
  <li><a href='<?=create_url('content/create')?>'>Create new content</a>
  <li><a href='<?=create_url('blog')?>'>View as blog</a>
</ul>
