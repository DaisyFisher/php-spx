--TEST--
Userland stats (PHP 7.0+)
--SKIPIF--
<?php
if (
    version_compare(PHP_VERSION, '8.0') < 0
) {
    die('skip this test is for PHP 8.0+ only');
}
?>
--ENV--
return <<<END
SPX_ENABLED=1
SPX_BUILTINS=1
SPX_METRICS=zuc,zuf,zuo
SPX_FP_FOCUS=zuo
END;
--FILE--
<?php

eval(<<<EOS
class foo {
  function __construct()
  {
    \$this->a = 0;
    \$this->b = 0;
  }
}
EOS
);

function bar()
{
    $a = 0;
    $b = 0;
}

function baz()
{
    $a = 0;
    $b = 0;
    $c = 0;
}

$a = 0;
$b = 0;
$c = 0;
$d = 0;

?>
--EXPECTF--
*** SPX Report ***

Global stats:

  Called functions    :        5
  Distinct functions  :        5

  ZE class count      :        1
  ZE func. count      :        3
  ZE opcodes count    :       17

Flat profile:

 ZE class count      | ZE func. count      | ZE opcodes count    |
 Inc.     | Exc.     | Inc.     | Exc.     | Inc.     | *Exc.    | Called   | Function
----------+----------+----------+----------+----------+----------+----------+----------
        0 |        0 |        2 |        2 |       12 |       12 |        1 | ::zend_compile_file
        1 |        1 |        1 |        1 |        5 |        5 |        1 | ::zend_compile_string
        1 |        0 |        1 |        0 |        5 |        0 |        1 | %s/spx_%s.php
        0 |        0 |        0 |        0 |        0 |        0 |        1 | %s/spx_%s.php(%d) : eval()'d code
        0 |        0 |        0 |        0 |        0 |        0 |        1 | ::php_request_shutdown