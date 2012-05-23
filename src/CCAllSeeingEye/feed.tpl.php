<?php foreach($feeds['sites'] as $site): ?>
<section>
<h1><?=$site->name?></h1>
<p><?=$site->description?></p>
<p class='silent smaller'><em>Informationen hämtas från: <code><a href='<?=$site->feedurl?>'><?=$site->feedurl?></a></code></em></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<article>
<em><a class='no-style' href='<?=$item->site->url?>'><?=$item->site->name?><span class='silent smaller'>, <?=time_diff($item->date)?> ago.</span></a></em><br />
<h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
<a class='no-style' href='<?=$item->permalink?>'><p><?=$item->content?> [...]</p></a>
</article>
<?php endforeach; ?>

<?php endforeach; ?>
