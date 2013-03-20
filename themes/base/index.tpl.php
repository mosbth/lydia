<!doctype html>
<html lang='<?=get_language()?>' class='<?=html_classes()?> <?=modernizr_no_js()?>'> 
<head>
<meta charset='utf-8'/>
<title><?=get_title()?></title>
<link rel='shortcut icon' href='<?=theme_url($favicon)?>'/>
<link rel='stylesheet' type='text/css' href='<?=theme_url($stylesheet)?>'/>
<?=get_meta('description')?>
<?=get_meta('keywords')?>
<?=get_meta('robots')?>
<?=get_feed()?>
<?php if(isset($inline_style)): ?><style><?=$inline_style?></style><?php endif; ?>
<?=modernizr_include()?>
</head>
<body>

<header id='outer-wrap-header' role='banner'>
  <div id='inner-wrap-header'>
    <div id='header'>
      <div id='user-menu'><?=login_menu()?></div>
      <div id='banner'>
<?php if(isset($custom_banner)): ?>
        <?=$custom_banner?>
<?php else: ?>
        <?php if(isset($site_logo)):?><a href='<?=base_url()?>'><img id='site-logo' src='<?=theme_url($logo)?>' alt='<?=$site_logo_alt?>' width='<?=$site_logo_width?>' height='<?=$site_logo_height?>' /></a><?php endif;?>
        <?php if(isset($site_title)):?><span id='site-title'><a href='<?=base_url()?>'><?=$site_title?></a></span><?php endif;?>
        <?php if(isset($site_slogan)):?><span id='site-slogan'><?=$site_slogan?></span><?php endif;?>
<?php endif; ?>
      </div>
      <?php if(region_has_content('navbar1')): ?><nav id='navbar1' role='navigation'><?=render_views('navbar1')?></nav><?php endif; ?>
    </div>
  </div>
</header>

<?php if(region_has_content('navbar2')): ?>
<div id='outer-wrap-navbar' role='navigation'>
  <div id='inner-wrap-navbar'>
    <div id='navbar'>
      <nav id='navbar2'><?=render_views('navbar2')?></nav>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(region_has_content('breadcrumb')): ?>
<div id='outer-wrap-header-below' role='navigation'>
  <div id='inner-wrap-header-below'>
    <div id='header-below'>
      <nav id='breadcrumb'><?=render_views('breadcrumb')?></nav>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if(region_has_content('flash', 'flash-1', 'flash-2', 'flash-3', 'flash-4')): ?>
<div id='outer-wrap-flash' role='complementary'>
  <div id='inner-wrap-flash'<?=get_class_for_region('flash')?>>
    <?php if(region_has_content('flash')): ?><div id='flash'><?=render_views('flash')?></div><?php endif; ?>
    <?php if(region_has_content('flash-1')): ?><div id='flash-1'><?=render_views('flash-1')?></div><?php endif; ?>
    <?php if(region_has_content('flash-2')): ?><div id='flash-2'><?=render_views('flash-2')?></div><?php endif; ?>
    <?php if(region_has_content('flash-3')): ?><div id='flash-3'><?=render_views('flash-3')?></div><?php endif; ?>
    <?php if(region_has_content('flash-4')): ?><div id='flash-4'><?=render_views('flash-4')?></div><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<?php if(region_has_content('featured-first', 'featured-middle', 'featured-last')): ?>
<div id='outer-wrap-featured' role='complementary'>
  <div id='inner-wrap-featured'>
    <div id='featured-first'><?=render_views('featured-first')?></div>
    <div id='featured-middle'><?=render_views('featured-middle')?></div>
    <div id='featured-last'><?=render_views('featured-last')?></div>
  </div>
</div>
<?php endif; ?>

<div id='outer-wrap-main'>
  <div id='inner-wrap-main'>
    <?php if(region_has_content('primary')): ?><div id='primary' role='main'<?=get_class_for_region('primary')?>><?=get_messages_from_session()?><?=render_views('primary')?></div><?php endif; ?>
    <?php if(region_has_content('sidebar')): ?><div id='sidebar' role='complementary'><?=render_views('sidebar')?></div><?php endif; ?>
    <?php if(region_has_content('custom')):  ?><div id='custom'<?=get_class_for_region('custom')?>><?=render_views('custom')?></div><?php endif; ?>
  </div>
</div>

<?php if(region_has_content('triptych-first', 'triptych-middle', 'triptych-last')): ?>
<div id='outer-wrap-triptych' role='complementary'>
  <div id='inner-wrap-triptych'>
    <div id='triptych'>
      <div id='triptych-first'><?=render_views('triptych-first')?></div>
      <div id='triptych-middle'><?=render_views('triptych-middle')?></div>
      <div id='triptych-last'><?=render_views('triptych-last')?></div>
    </div>
  </div>
</div>
<?php endif; ?>

<footer id='outer-wrap-footer' class='footer' role='contentinfo'>
  <?php if(region_has_content('footer-column-one', 'footer-column-two', 'footer-column-three', 'footer-column-four', 'footer-column-five', 'footer-column-six', 'footer-column-seven',  'footer-column-eight')): ?>
  <div id='inner-wrap-footer-column'>
    <div id='footer-column-wrapper-one' class='footer-column-wrapper'>
      <div id='footer-column-one' class='footer-column'><?=render_views('footer-column-one')?></div>
      <div id='footer-column-two' class='footer-column'><?=render_views('footer-column-two')?></div>
      <div id='footer-column-three' class='footer-column'><?=render_views('footer-column-three')?></div>
      <div id='footer-column-four' class='footer-column'><?=render_views('footer-column-four')?></div>
    </div>
    <?php if(region_has_content('footer-column-five', 'footer-column-six', 'footer-column-seven',  'footer-column-eight')): ?>
    <div id='footer-column-wrapper-two' class='footer-column-wrapper'>
      <div id='footer-column-five' class='footer-column'><?=render_views('footer-column-five')?></div>
      <div id='footer-column-six' class='footer-column'><?=render_views('footer-column-six')?></div>
      <div id='footer-column-seven' class='footer-column'><?=render_views('footer-column-seven')?></div>
      <div id='footer-column-eight' class='footer-column'><?=render_views('footer-column-eight')?></div>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <div id='inner-wrap-footer'>
    <div id='footer'><?=render_views('footer')?><?=get_debug()?></div>
  </div>
</footer>

<?=get_tracker()?>
</body>
</html>
