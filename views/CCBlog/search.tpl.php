<section class='ly-search'>

<header class='ly-blog-header'>
  <h1><?=$intro['title']?></h1>
  <p><?=$intro['content']?></p>
  <?=$form->GetHTML(array('use_fieldset'=>false))?>
</header>

<?php if($hits):?>
<?php foreach($contents as $val):?>
<article class='ly-search-post'>

  <header class='ly-search-post-header'>
    <h2><a href='<?=create_url(null, $val['key'])?>'><?=esc($val['title'])?></a></h2>
  </header>

  <span class='ly-search-url'><?=create_url(null, $val['key'])?></span><br/>
  <?=$val['snippet']?>

</article>
<?php endforeach; ?>
<?php endif;?>

<footer>
<?php if($hits): ?>
  <p><?=t('Displaying hits !first - !last from a total of !total.', array('!first' => $first_hit, '!last' => $last_hit, '!total' => $total_hits));?></p>
  <?=pagination($first_page, $current_page, $last_page, $pagination_url)?>
<?php elseif($did_search): ?>
  <p><?=t('This search gave no hits. Try another search condition.')?></p>
<?php else: ?>
  <p><?=t('Search by combining keywords, you may mix AND OR and "" to enhance your search query.')?></p>
<?php endif; ?>
</footer>

</section>


