<h1>Module Manager</h1>


<h2>About</h2>
<p><strong>Module Manager</strong> displays information on modules and enable managing of all Lydia modules. Lydia is made up of modules. Each module
has its own subdirectory in the <code>src</code>-directory.</p>


<h2>Enabled controllers</h2>
<p>The controllers make up the public API of this website. Here is a list of the enabled 
controllers and their methods. You enable and disable controllers in 
<code>site/config.php</code>.</p>

<ul>
<?php foreach($controllers as $key => $val): ?>
  <li><a href='<?=create_url($key)?>'><?=$key?></a></li>

  <?php if(!empty($val)): ?>
  <ul>
  <?php foreach($val as $method): ?>
    <li><a href='<?=create_url($key, $method)?>'><?=$method?></a></li> 
  <?php endforeach; ?>		
  </ul>
  <?php endif; ?>
  
<?php endforeach; ?>		
</ul>
