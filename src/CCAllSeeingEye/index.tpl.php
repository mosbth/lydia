<section>
<h1>Senaste nytt från webbvärlden</h1>
<p>Nytt och noterat från RSS-flödet från några av de mest inflytelserika webbplatserna 
inom webbprogrammering och webbutveckling.</p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<article>
<em><a class='no-style' href='<?=$item->site->url?>'><?=$item->site->name?><span class='silent smaller'>, <?=time_diff($item->date)?> ago.</span></a></em><br />
<h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
<a class='no-style' href='<?=$item->permalink?>'><p><?=$item->content?> [...]</p></a>
<p class='silent smaller'><em>Kategori: <a class='no-style' href='<?=$item->site->category->url?>'><?=$item->site->category->name?></a></em></p>
</article>
<?php endforeach; ?>
