<?php
/**
 * To manage the module.
 */
class CMContentModule extends CMContent {

  /**
   * Manage install/update/deinstall and equal actions.
   *
   * @param string $action the action to carry out.
   */
  public function Manage($action=null) {
    switch($action) {
      case 'install': 
        $this->db->ExecuteQuery(self::SQL('create table category'));
        $this->db->ExecuteQuery(self::SQL('create table content'));
        $this->db->ExecuteQuery(self::SQL('create table docs'));
        $ret = CMModules::CreateModuleDirectory(get_parent_class(), 'txt');
        $status = 'success';
        $msg = t('Successfully created the database tables. Untouched if the already existed.');
        if($ret === null) {
          $msg .= ' '.t('Directory in site/data already exists.');
        } elseif($ret === false) {
          $status = 'error';
          $msg .= ' '.t('Failed to create directory in site/data.');
        } else {
          $msg .= ' '.t('Created directory in site/data.');
        }
        return array($status, $msg);
      break;
      
      case 'rebuild-index': 
        $this->db->ExecuteQuery(self::SQL('drop table docs'));
        $this->db->ExecuteQuery(self::SQL('create table docs'));
        $res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select id to index'));
        $c = new CMContent();
        foreach($res as $row) {
          $c->LoadById($row['id']);
          $this->db->ExecuteQuery(self::SQL('insert docs'), array($c['id'], $c['key'], $c['title'], $c->GetPureText()));
        }
        $this->db->ExecuteQuery(self::SQL('optimize docs'));
        $status = 'success';
        $msg = t('Successfully recreated the fulltext search index.');
        return array($status, $msg);
      break;
      
      case 'sample': 
        $this->db->ExecuteQuery(self::SQL('insert category'), array('new-category', 'New category'));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 1, 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 1, 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 1, 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 1, 'Home page', "This is a demo page, this could be your personal home-page.\n\nLydia is a PHP-based MVC-inspired Content management Framework, watch the making of Lydia at: http://dbwebb.se/lydia/tutorial.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 1, 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 1, 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", null, 'plain', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 1, 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", null, 'bbcode', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 1, 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", null, 'htmlpurify', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('markdown', 'page', 1, 'Page with Markdown', "This is a demo of the markdown filter.\n\n* list\n* list\n", null, 'markdown', $this->user['id']));
        $this->db->ExecuteQuery(self::SQL('insert content'), array('file', 'page', 1, 'Page from file', "This page includes data from a file.", 'hello_world/hello.txt', 'markdown', $this->user['id']));
        $ret1 = CMModules::CreateModuleDirectory(get_parent_class(), 'txt/hello_world');
        $text = "##Hello World\nThis a sample page included from file.";
        $path = LYDIA_DATA_PATH.'/'.get_parent_class()."/txt/hello_world/hello.txt";
        $ret2 = file_put_contents($path, $text);
        $status = ($ret1 === false || $ret2 === false) ? 'error' : 'success';
        $msg = ($ret1 === false || $ret2 === false) ? t('Failed to create file in site/data.') : t('Sample file created.');
        return array('success', t('Successfully inserted sample content into database.').' '.$msg);
      break;
      
      case 'export-db':
        $manager = new CMModules();
        $sql  = "-- #### Start Module " . get_parent_class() . "\n";
        $sql .= $manager->DumpTableToSQL(self::SQL('table name content'), self::SQL('export table content'), self::SQL('create table content'), self::SQL('drop table content'));
        $sql .= $manager->DumpTableToSQL(self::SQL('table name category'), self::SQL('export table category'), self::SQL('create table category'), self::SQL('drop table category'));
        $sql .= "-- #### End Module " . get_parent_class() . "\n\n";
        return array('success', t('Successfully exported data as SQL INSERT commands.'), $sql);
      break;
      
      case 'supported-actions':
        $actions = array('install', 'sample', 'export-db', 'rebuild-index');
        return array('success', t('Supporting the following actions: !actions.', array('!actions'=>implode(', ', $actions))), 'actions'=>$actions);
      break;

      default:
        return array('info', t('Action not supported by this module.'));
      break;
    }
  }


}
