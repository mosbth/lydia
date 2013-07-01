<?php
/**
 * Class to log what happens.
 *
 * @package LydiaCore
 */
class CLog {

  /**
   * Properties
   *
   */
  private $timestamp = array();
  private $pos = 0;



  /**
   * Constructor
   *
   */
  public function __construct() {

  }



  /**
   * Timestamp, log a event with a time.
   *
   * @param string $domain is the module or class.
   * @param string $where a more specific place in the domain.
   * @param string $comment on the timestamp.
   *
   */
  public function Timestamp($domain, $where, $comment=null) {
    $now = microtime(true);

    $this->timestamp[] = array(
      'domain'  => $domain,
      'where'   => $where,
      'comment' => $comment,
      'when'    => $now,
      'memory'  => memory_get_usage(true),
    );

    if($this->pos) {
      $this->timestamp[$this->pos - 1]['memory-peak'] = memory_get_peak_usage(true);
      $this->timestamp[$this->pos - 1]['duration']    = $now - $this->timestamp[$this->pos - 1]['when'];
    }
    
    $this->pos++;
  }



  /**
   * Print all timestamp to a table.
   *
   * @return string with a html-table to display all timestamps.
   *
   */
  public function TimestampAsTable() {

    $prev = $first = $this->timestamp[0]['when'];
    $last = $this->timestamp[count($this->timestamp) - 1]['when'];

    $html .= "<table class=table><caption>Timestamps</caption><tr><th>Domain</th><th>Where</th><th>When (sec)</th><th>Duration (sec)</th><th>Percent</th><th>Memory (MB)</th><th>Memory peak (MB)</th><th>Comment</th></tr>";
    $right = ' style="text-align: right;"';
    $total = array('domain' => array(), 'where' => array());

    foreach($this->timestamp as $val) {
      $when     = $val['when'] - $first;
      $duration = round($val['duration'], 3);
      $percent  = round(($when) / ($last - $first) * 100);
      $memory   = round($val['memory'] / 1024 / 1024, 2);
      $peak     = round($val['memory-peak'] / 1024 / 1024, 2);
      $when     = round($when, 3);
      $html .= "<tr><td>{$val['domain']}</td><td>{$val['where']}</td><td{$right}>{$when}</td><td{$right}>{$duration}</td><td{$right}>{$percent}</td><td{$right}>{$memory}</td><td{$right}>{$peak}</td><td>{$val['comment']}</td></tr>";
      $prev = $val['when'];

      @$total['domain'][$val['domain']] += $duration;
      @$total['where'][$val['where']] += $duration;
    }
    $html .= "</table><table class=table><caption>Duration per domain</caption><tr><th>Domain</th><th>Duration</th><th>Percent</th></tr>";

    arsort($total['domain']);
    foreach($total['domain'] as $key => $val) {
      $percent = round($val / ($last - $first) * 100, 1);
      $html .= "<tr><td>{$key}</td><td>{$val}</td><td>{$percent}</td></tr>";
    }
    $html .= "</table><table class=table><caption>Duration per area</caption><tr><th>Area</th><th>Duration</th><th>Percent</th></tr>";

    arsort($total['where']);
    foreach($total['where'] as $key => $val) {
      $percent = round($val / ($last - $first) * 100, 1);
      $html .= "<tr><td>{$key}</td><td>{$val}</td><td>{$percent}</td></tr>";
    }
    $html .= "</table>";

    return $html;
  }



  /**
   * Print page load time.
   *
   * @return string with the page load time.
   *
   */
  public function PageLoadTime() {
    /*$now = microtime(true);
    $flash = $ly->session->GetFlash('timer');
    $redirect = $flash ? round($flash['redirect'] - $flash['first'], 3) . ' secs + x + ' : null;
    $total = $flash ? round($now - $flash['first'], 3) . ' secs. Per page: ' : null;
    $html .= "<p>Page was loaded in {$total}{$redirect}" . round($now - $ly->timer['first'], 3) . " secs.</p>";*/

    $first = $this->timestamp[0]['when'];
    $last = $this->timestamp[count($this->timestamp) - 1]['when'];
    $loadtime = round($last - $first, 3);
    $html = "<p>Page was loaded in {$loadtime} secs.</p>";
    return $html;
  }



  /**
   * Print memory peak.
   *
   * @return string with the memory peak.
   *
   */
  public function MemoryPeak() {
    /*    $flash = $ly->session->GetFlash('memory');
    $flash = $flash ? round($flash/1024/1024, 2) . ' Mbytes + ' : null;
    $html .= "<p>Peek memory consumption was $flash" . round(memory_get_peak_usage(true)/1024/1024, 2) . " Mbytes.</p>";*/

    $peek = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
    $html = "<p>Peek memory consumption was {$peek} MB.</p>";
    return $html;
  }



}

