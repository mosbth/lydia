<div class='box'>

<h4><a href='<?=create_url(isset($title_url) ? $title_url : $base_url)?>'><?=$title?></a></h4>

<?php if($contents != null):?>
<ul>
<?php foreach($contents as $val):?>
<li><a href='<?=create_url($base_url, $val['key'])?>' title='<?=format_date($val->PublishTime()) . "\n" . $val->GetExcerpt()?>...'><?=esc($val['title'])?></a></li>
<?php endforeach; ?>
</ul>

<?php else:?>
<p><?=t('No posts exists.')?></p>
<?php endif;?>

</div>
