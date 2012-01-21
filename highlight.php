<?php

/* place in application/config/hooks.php:
$hook['display_override'] = array(
  'class' => 'highlight',
  'function' => 'hook',
  'filename' => 'highlight.php',
  'filepath' => 'hooks/ci-syntax-highlight',
  'params' => array()
);
*/
class highlight {

  public function highlight() {
    if (!class_exists('luminous')) {
      require_once dirname(__FILE__) . '/luminous/luminous.php';
    }
  }
  private function hook_cb($matches) {
    $meta = $matches[2];
    $code = $matches[3];
    
    // parse the classes, don't worry about this if it's a [code] 
    if (strlen($matches[0]) && $matches[0][0] === '<') {
      // code in <...> tags are legit. html so we need to unescape whatever they
      // had to escape
      $code = htmlspecialchars_decode($code);
      if (!preg_match('/class=([\'"])(.*?)(\\1)/', $meta, $m)) return $matches[0];
      $classes = preg_split('/\s+/', $m[2]);
      if (!in_array('highlight', $classes)) return $matches[0];
    }
    $language = 'plain';
    if (preg_match('/lang(uage)?=(.*)/', $meta, $m)) {
      $language = $m[2];
      if (strlen($language) && ($language[0] === '"' || $language[0] === "'")) {
        if (($pos = strpos($language, $language[0], 1)) !== false) {
          $language = substr($language, 1, $pos-1);
        }
      }
    }
    return luminous::highlight($language, $code);
  }

  public function hook($params = array()) {
    $CI = & get_instance();
    $output = $CI->output->get_output();
    if (!isset($params['header']) || $params['header'] === true) {
      if (isset($params['theme'])) 
        luminous::set('theme', $params['theme']);
      if (!function_exists('base_url')) {
        $CI->load->helper('url');
      }
      luminous::set('relative-root', 
        base_url() . 'application/hooks/ci-syntax-highlight/luminous/');
      $head = luminous::head_html();
      // insert the stylesheets
      $output = preg_replace('%</head%i',
        "$head\n" . '$0', $output, 1);
    }
    $exps = array(
      // [code] .. [/code]
      "/
        \[(code)(.*?)\][ \t]*(?:[\r\n]|\r\n)?
        (.*?)
        \s*
        \[\/code\]
      /xs",
      // <pre> or <code>
      "/
        <(pre|code)(.*?)>[ \t]*(?:[\r\n]|\r\n)?
        (.*?)
        \s*
        <\/\\1>
      /xs");
    foreach($exps as $e) {
      $output = preg_replace_callback($e, array($this, 'hook_cb'), $output);
    }
    echo $output;
  }
}
