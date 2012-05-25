<section>
<h1><?=t('RSS aggregation though the All-Seeing-Eye')?></h1>
<p><?=t('The module CCAllSeeingEye is a Lydia application which does RSS aggregation. Make an app-class who use this class, create a config.php-file to choose your feeds and customize the views to adapt it.')?></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<article>
<em><a class='no-style' rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?><span class='silent smaller'>, <?=time_diff($item->date)?>.</a></silent></em><br />
<h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
<a class='no-style-black' rel='nofollow' href='<?=$item->permalink?>'><p><?=$item->content?> [...]</p></a>
<p class='silent smaller'><em><a class='no-style' href='<?=$item->site->category->url?>'><?=t('Category:') . ' ' . $item->site->category->name?></a></em></p>
</article>
<?php endforeach; ?>
