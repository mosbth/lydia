<!doctype html>
<html lang='en'> 
<head>
  <meta charset='utf-8'/>
  <title><?=$title?></title>
   <link rel='shortcut icon' href='<?=$favicon?>'/>
  <link rel='stylesheet' href='<?=$stylesheet?>'/>
</head>
<body>

<div id='outer-wrap-header'>
  <div id='inner-wrap-header'>
    <div id='header'>
      <div id='login-menu'><?=login_menu()?></div>
      <div id='banner'>
        <a href='<?=base_url()?>'><img id='site-logo' src='<?=$logo?>' alt='logo' width='<?=$logo_width?>' height='<?=$logo_height?>' /></a>
        <span id='site-title'><a href='<?=base_url()?>'><?=$header?></a></span>
        <span id='site-slogan'><?=$slogan?></span>
      </div>
    </div>
  </div>
</div>

<div id='outer-wrap-flash'>
  <div id='inner-wrap-flash'>
    <div id='flash'>Flash</div>
  </div>
</div>

<div id='outer-wrap-featured'>
  <div id='inner-wrap-featured'>
    <div id='featured-first'>Featured 1</div>
    <div id='featured-middle'>Featured 2</div>
    <div id='featured-last'>Featured 3</div>
  </div>
</div>

<div id='outer-wrap-main'>
  <div id='inner-wrap-main'>
    <div id='primary'>
      <?=get_messages_from_session()?>
      <?=@$main?>
      <?=render_views()?>
    </div>
    <div id='sidebar'>Sidebar</div>
  </div>
</div>

<div id='outer-wrap-triptych'>
  <div id='inner-wrap-triptych'>
    <div id='triptych-first'>Triptych first</div>
    <div id='triptych-middle'>Triptych middle</div>
    <div id='triptych-last'>Triptych last</div>
  </div>
</div>

<div id='outer-wrap-footer-column'>
  <div id='inner-wrap-footer-column'>
    <div id='footer-column-one'>Footer column one</div>
    <div id='footer-column-two'>Footer column two</div>
    <div id='footer-column-three'>Footer column three</div>
    <div id='footer-column-four'>Footer column four</div>
  </div>
</div>

<div id='outer-wrap-footer'>
  <div id='inner-wrap-footer'>
    <div id='footer'><?=$footer?><?=get_debug()?></div>
  </div>
</div>

</body>
</html>