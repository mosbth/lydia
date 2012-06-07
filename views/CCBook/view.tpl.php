<h1><?=t('Book overview')?></h1>

<h2><?=$book['title']?></h2>

<p class='smaller-text'><em>
<?=t('This book were created by !owner !time ago.', array('!owner'=>$book['owner'], '!time'=>time_diff($book['created'])))?>
<?php if(isset($book['updated'])):?>
 Last updated <?=time_diff($book['updated'])?> ago.
<?php endif; ?>
</em></p>

<p><?=t('Key: !key', array('!key'=>$book['key']))?></p>

<p>
<a href='<?=create_url('book', 'edit', $book['id'])?>'><?=t('Edit book details.')?></a>
</p>


<h3><?=t('Chapters')?></h3>

<ul>
<?php foreach($book['chapters'] as $chapter): ?>
<li><?=$chapter['position']?>, <?=$chapter['title']?> (<?=$chapter['idContent']?>) <a href='<?=create_url('book', 'chapter/edit', $book['id'] . '/' . $chapter['id'])?>'>edit</a></li>  
<?php endforeach; ?>
</ul>

<p>
<a href='<?=create_url('book', 'chapter/add', $book['id'])?>'>Add chapter</a>
</p>

<p>
<a href='<?=create_url('book/create')?>'>Create new book</a>
<a href='<?=create_url('book')?>'>View all books</a>
</p>
