<h1>Done - finish by disabling the installation phase in the configuration file</h1>

<p>The installation process is done. To move on and start using Lydia you must first disable the controller used for the installation phase.
  You do this in the config-file <code>site/config.php</code></h1>

<p>Open the config-file and change the setting of the <code>install</code>-controller from <code>'enabled' => true</code> to  <code>'enabled' => false</code>. You can also put a comment infront of the whole line. Do the opposite if you ever need to run this installation process again.</p>

<pre><code>/**
 * Define the controllers, their classname and enable/disable them.
 *
 * The array-key is matched against the url, for example: 
 * the url 'developer/dump' would instantiate the controller with the key "developer", that is 
 * CCDeveloper and call the method "dump" in that class. This process is managed in:
 * $ly->FrontControllerRoute();
 * which is called in the frontcontroller phase from index.php.
 */
$ly->config['controllers'] = array(
  'install'   => array('enabled' => true,'class' => 'CCInstall'),
</code></pre>

<p>Have fun with your Lydia installation and let me know what you think about it!</p>

<p class='info'>The install controller is currently <strong><?=$status?></strong>.</p>

<p>
<a href='<?=create_url(null, 'step6')?>'>&laquo; Back</a>&nbsp;&nbsp;&nbsp;
<a href='<?=create_url('index', 'step7')?>'>Check to see if the install-controller is disabled (you will leave this page if it is)...</a>&nbsp;&nbsp;&nbsp;
</p>
