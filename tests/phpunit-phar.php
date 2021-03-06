<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Jonathan Vollebregt (jnvsor@gmail.com), Rokas Šleinius (raveren@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

\putenv('KINT_PHAR_TEST=1');

/**
 * This require loads a built file before phpunit, since using phpunit
 * directly will load composer and automatically start using the loose files.
 */
require __DIR__.'/../build/kint.phar';

$composer = require __DIR__.'/../vendor/autoload.php';

// Register the composer autoloader after the phar autoloader
$composer->unregister();
$composer->register();

// All of this to trim the shabang off the script
$bin = \file_get_contents(__DIR__.'/../vendor/bin/phpunit');
$bin = \explode("\n", $bin);

if ('#!' == \substr($bin[0], 0, 2)) {
    \array_shift($bin);
}

$bin = \implode("\n", $bin);

\file_put_contents(__DIR__.'/phpunit.tmp', $bin);

require __DIR__.'/phpunit.tmp';

\unlink(__DIR__.'/phpunit.tmp');
