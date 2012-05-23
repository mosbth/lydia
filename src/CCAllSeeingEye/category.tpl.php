<?php foreach($feeds['categories'] as $category): ?>
<section>
<h1><?=$category['name']?></h1>
<p><?=$category['description']?></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<article>
<em><a class='no-style' href='<?=$item->site->url?>'><?=$item->site->name?><span class='silent smaller'>, <?=time_diff($item->date)?> ago.</span></a></em><br />
<h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
<a class='no-style' href='<?=$item->permalink?>'><p><?=$item->content?> [...]</p></a>
</article>
<?php endforeach; ?>

<?php endforeach; ?>
