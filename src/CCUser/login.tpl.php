<h1>Login</h1>
<p>Here should a login form be, but for now you can login using these links.</p>
<ul>
  <li><a href='<?=create_url('user/login/doe/doe')?>'>Login as doe:doe (should work)</a>
  <li><a href='<?=create_url('user/login/root/root')?>'>Login as root:root (should work)</a>
  <li><a href='<?=create_url('user/login/root@dbwebb.se/root')?>'>Login as root@dbwebb.se:root (should work)</a>
  <li><a href='<?=create_url('user/login/admin/root')?>'>Login as admin:root (should fail, wrong akronym)</a>
  <li><a href='<?=create_url('user/login/root/admin')?>'>Login as admin:root (should fail, wrong password)</a>
  <li><a href='<?=create_url('user/login/admin@dbwebb.se/root')?>'>Login as admin@dbwebb.se:root (should fail, wrong email)</a>
</ul>


