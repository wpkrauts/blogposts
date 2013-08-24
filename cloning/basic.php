<?php # -*- coding: utf-8 -*-
error_reporting( E_ALL );
header( 'Content-Type: text/plain;charset=utf-8' );

// see http://wpkrauts.com/?p=573

$a1 = new stdClass;
$a1->foo = 'hello';
print $a1->foo . PHP_EOL; // "hello"

$b1 = clone $a1;
print $b1->foo . PHP_EOL; // "hello", value is a copy
// change the value
$b1->foo = 'world';

print $a1->foo . PHP_EOL; // still "hello"
print $b1->foo . PHP_EOL; // now "world"