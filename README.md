FineDiff
========

Originally written by Raymond Hill ([https://github.com/gorhill/PHP-FineDiff](https://github.com/gorhill/PHP-FineDiff)) FineDiff has been tweaked to bring it up to date with the modern world. That means documented, nicely formatted, tested code that can be easily extended.

[![Build Status](https://travis-ci.org/cogpowered/FineDiff.png?branch=master)](https://travis-ci.org/cogpowered/FineDiff)

Installation
------------

**Composer**

The preferred way of using FineDiff is through [Composer](http://getcomposer.org).

Add the following to your composer.json file:

```json
{
    "require": {
        "cogpowered/finediff": "0.3.*"
    }
}
```

Upgrading
---------

**0.3.x** introduces a backwards incompatible version, so if you have stored opcodes do not upgrade!

`0.3.x` fixes a double encoding issue that generates a longer opcode than is needed.

Usage
-----

**Render HTML**


Render as HTML the difference between two strings:

```php
$diff = new cogpowered\FineDiff\Diff;
echo $diff->render('string one', 'string two');
```

This would then output:

```html
string <ins>tw</ins>o<del>ne</del>
```

You could change the granularity to `cogpowered\FineDiff\Granularity\Word` so the output is:

```html
string <del>one</del><ins>two</ins>
```

You do this by passing it into the Diff constructor:

```php
$granularity = new cogpowered\FineDiff\Granularity\Word;
$diff        = new cogpowered\FineDiff\Diff($granularity);
```

**Grab opcode instructions**

Opcode instructions are what tell FineDiff how to change one string into another.

```php
$diff = new cogpowered\FineDiff\Diff;
echo $diff->getOpcodes('string one', 'string two');
```

This would then output:

```html
c7d3i3:two
```

Render text using the opcodes:
```php
$render = new cogpowered\FineDiff\Render\Text;
echo $render->process('string one', 'c7d3i3:two');
```

Would output:
```html
string two
```

Same with HTML:
```php
$render = new cogpowered\FineDiff\Render\Html;
echo $render->process('string one', 'c7d3i3:two');
```

License
-------

Copyright (c) 2011 Raymond Hill (http://raymondhill.net/blog/?p=441)
Copyright (c) 2013 Rob Crowe (http://cogpowered.com)

Licensed under The MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
