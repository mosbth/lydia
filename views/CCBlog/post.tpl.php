<?php if($content != null):?>

<?php 
$title = esc($content['title']); 
$permalink = get_permalink();
?>

<article class='ly-blog' itemscope itemtype='http://schema.org/Article'>

  <header class='ly-blog-post-header'>
    <h1 itemprop='name headline'><?=$title?></h1>
    <?php 
      $date = $order_by_updated ? $content['updated'] : $content['created']; 
      $what = $order_by_updated ? t('Updated') : t('Published'); 
    ?>
    <p class='ly-blog-post-meta-header'>
      <?=t('By')?> <span class="vcard author"><span class="fn" itemprop='author' itemscope itemtype='http://schema.org/Person'><span itemprop='name'><!--<a href='http://dbwebb.se/v2/author/mos' rel='author'>--><?=$content['owner_name']?><!--</a>--></span></span></span>.
      <?=$what?> <time itemprop='datePublished' datetime='<?=$date?>'><?=format_date($date)?></time>
    </p>
  </header>

  <?=$content['data_filtered']?>

  <footer class='ly-blog-post-footer'>
    <p class='ly-blog-post-meta-footer'><a href='<?=create_url(null, m('category'), $content['category_key'])?>'><?=t('Category: @category_name', array('@category_name' => $content['category_title']))?></a>
    <?php if($user_is_admin || $contents->CurrentUserIsOwner()): ?>
    | <a href='<?=create_url("content/edit/{$content['id']}")?>'><?=t('edit')?></a></p>
    <?php endif; ?>

  </footer>

  <?=get_author_byline($author)?>



  <footer class='share-icons'>
    <ul class='icons'>
    <li><h3>Dela denna artikel på </h3></li>
    <li><a href='<?=get_facebook_share_link($permalink, $title)?>'><img src='/img/glyphicons/png/glyphicons_390_facebook.png' alt='facebook-icon' title='Dela på Facebook' width='24' height='24'/></a></li>
    <li><a href='<?=get_twitter_share_link($permalink, $title)?>'><img src='/img/glyphicons/png/glyphicons_392_twitter.png' alt='twitter-icon' title='Dela på Twitter' width='24' height='24'/></a></li>
    <li><a href='<?=get_googleplus_share_link($permalink, $title)?>'><img src='/img/glyphicons/png/glyphicons_362_google+_alt.png' alt='google+-icon' title='Dela på Google+' width='24' height='24'/></a></li>
    <li><a href='<?=get_linkedin_share_link($permalink, $title)?>'><img src='/img/glyphicons/png/glyphicons_377_linked_in.png' alt='linkedin-icon' title='Dela på LinkedIn' width='24' height='24'/></a></li>
    </ul>
  </footer>

</article>

<?php else:?>
<p><?=t('No such post.')?></p>
<?php endif;?>


