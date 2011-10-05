# Syntax highlighting for CodeIgniter projects

This small class adds syntax highlighting support for CodeIgniter projects
via CodeIgniter's hook system.

The way it works is to intercept output, and replace anything within certain
code blocks with syntax highlighted code.

## Usage

The recognised code blocks are:

```
[code language='php']
<?php echo 'hello'; ?>
[/code]

<pre class='highlight' language='php'>
&lt;?php echo 'hello'; &gt;
</pre>

<code class='highlight' language='php'>
&lt;?php echo 'hello'; &gt;
</code>
```

Note that inside of the HTML tags (pre and code), the highlight class must be
set (and in quotes!), and the contents must be entity-escaped
(as from php's htmlspecialchars()).

The CSS stylesheets are injected into your page's HEAD element, HOPEFULLY.

## Installation

To use it, you will need to make the following adjustments:

Put ci-syntax-highlight into application/hooks/, and create and make writable
ci-syntax-highlight/luminous/cache.

In case you have not already, you will need to alter
application/config/config.php and set: $config['enable_hooks'] = TRUE;

Add the following into application/config/hooks.php to enable the hook:

```php
<?php
$hook['display_override'] = array(
  'class' => 'highlight',
  'function' => 'hook',
  'filename' => 'highlight.php',
  'filepath' => 'hooks/ci-syntax-highlight',
  'params' => array()
);
```

'params' is an associative array. The valid settings are:

 + 'theme' => 'themefile.css' (themes are under luminous/style)
 + 'header' => true|false (if false, the HEAD code is not inserted. Default 
  true)



