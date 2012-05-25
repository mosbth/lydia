<?php foreach($feeds['sites'] as $site): ?>
<section>
<h1><?=$site->name?></h1>
<p><?=$site->description?></p>
<p class='silent smaller'><em><?=t('Details for !site:', array('!site'=>$site->name))?> 
<a class='no-style' href='<?=$site->siteurl?>'><?=t('Website')?></a> 
<a class='no-style' href='<?=$site->feedurl?>'><?=t('RSS feed')?></a>
</em></p>
</section>

<?php foreach($feeds['items'] as $item): ?>
<article>
<em><a class='no-style' rel='nofollow' href='<?=$item->site->url?>'><?=$item->site->name?><span class='silent smaller'>, <?=time_diff($item->date)?> ago.</span></a></em><br />
<h2><a class='no-style' href='<?=$item->permalink?>'><?=$item->title?></a></h2>
<a class='no-style-black' rel='nofollow' href='<?=$item->permalink?>'><p><?=$item->content?> [...]</p></a>
</article>
<?php endforeach; ?>

<?php endforeach; ?>
