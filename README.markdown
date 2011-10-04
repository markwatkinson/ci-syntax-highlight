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
set, and the contents must be entity-escaped (as php's htmlspecialchars()).

## Installation

To use it, you will need to make the following adjustments:

Create application/hooks/ci-syntax-highlight/ and insert highlight.php and
luminous/ into that directory (note, you may need to give writable
permissions to luminous/cache)

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



