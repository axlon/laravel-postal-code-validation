<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (!class_exists('\PHPUnit\Framework\Constraint\Constraint')) {
    class_alias('\PHPUnit_Framework_Constraint', '\PHPUnit\Framework\Constraint\Constraint');
}

if (!class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}
