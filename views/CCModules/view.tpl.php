<?php if(!is_array($module)): ?>

<p>404. So such module.</p>

<?php else: ?>

<h1>Module: <?=$module['name']?></h1>

<h2>Description</h2>

<!-- <p>File: <code><?=$module['filename']?></code></p> -->

<p><?=nl2br($module['doccomment'])?></p>


<h2>Details</h2>

<table>
<caption>Details of module.</caption>
<thead><tr><th>Characteristics</th><th>Applies to module</th></tr></thead>
<tbody>
  <tr><td>Part of Lydia Core modules</td><td><?=$module['isLydiaCore']?'Yes':'No'?></td></tr>
  <tr><td>Part of Lydia CMF modules</td><td><?=$module['isLydiaCMF']?'Yes':'No'?></td></tr>
  <tr><td>Implements interface(s)</td><td><?=empty($module['interface'])?'No':implode(', ', $module['interface'])?></td></tr>
  <tr><td>Controller</td><td><?=$module['isController']?'Yes':'No'?></td></tr>
  <tr><td>Model</td><td><?=$module['isModel']?'Yes':'No'?></td></tr>
  <tr><td>Has SQL</td><td><?=$module['hasSQL']?'Yes':'No'?></td></tr>
  <tr><td>Manageable as a module</td><td><?=$module['isManageable']?'Yes':'No'?></td></tr>
</tbody>
</table>


<?if(!empty($module['publicMethods'])): ?>
<h2>Public methods</h2>
<?php foreach($module['methods'] as $method): ?>
<?php if($method['isPublic']): ?>
<h3><?=$method['name']?></h3>
<p><?=nl2br($method['doccomment'])?></p>
<p>Implemented through lines: <?=$method['startline']?> - <?=$method['endline']?>.</p>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>


<?if(!empty($module['protectedMethods'])): ?>
<h2>Protected methods</h2>
<?php foreach($module['methods'] as $method): ?>
<?php if($method['isProtected']): ?>
<h3><?=$method['name']?></h3>
<p><?=nl2br($method['doccomment'])?></p>
<p>Implemented through lines: <?=$method['startline']?> - <?=$method['endline']?>.</p>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>


<?if(!empty($module['privateMethods'])): ?>
<h2>Private methods</h2>
<?php foreach($module['methods'] as $method): ?>
<?php if($method['isPrivate']): ?>
<h3><?=$method['name']?></h3>
<p><?=nl2br($method['doccomment'])?></p>
<p>Implemented through lines: <?=$method['startline']?> - <?=$method['endline']?>.</p>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>


<?if(!empty($module['staticMethods'])): ?>
<h2>Static methods</h2>
<?php foreach($module['methods'] as $method): ?>
<?php if($method['isStatic']): ?>
<h3><?=$method['name']?></h3>
<p><?=nl2br($method['doccomment'])?></p>
<p>Implemented through lines: <?=$method['startline']?> - <?=$method['endline']?>.</p>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>


<?php endif; ?>
