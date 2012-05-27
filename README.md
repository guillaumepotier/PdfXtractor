PdfXtractor
===========

**PdfXtractor** is a PHP class that relies on GhostScript. This is a handy wrapper that
allows PDF conversion to JPEG files.

[![Build Status](https://secure.travis-ci.org/guillaumepotier/PdfXtractor.png)](http://travis-ci.org/guillaumepotier/PdfXtractor)

Installation
------------

The recommended way to install PdfXtractor is through composer.

Just create a `composer.json` file for your project:

``` json
{
    "require": {
        "guillaumepotier/PdfXtractor": "*"
    }
}
```

And run these two commands to install it:

``` bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

Now you can add the autoloader, and you will have access to the library:

``` php
<?php

require 'vendor/autoload.php';
```

If you don't use neither **Composer** nor a _ClassLoader_ in your application, just
require the provided autoloader:

``` php
<?php

require_once 'src/autoload.php';
```

You're done.

Usage
-----

You'll just need to specify the pdf you want to convert, where you want to dump the .jpg
files generated and under which name:

``` php
<?php

$pdfXtractor = new PdfXtractor\PdfXtractor();
$pdfXtractor->load(__DIR__.'/file.pdf')->set(__DIR__.'/output', 'extract');
$pdfXtractor->extract();
```

Unit Tests
----------

To run unit tests, set your proper phpunit.xml file by doing:

```
cp phpunit.xml.dist phpunit.xml
vi phpunit.xml
```

Set the `GS_BIN` you'll use in your environement. Save and launch then the following
command:

```
phpunit
```

TODO
----

Need to better rationalize the GhostScript binary used in function of the user OS. Maybe
integrate a shell script to build gs properly for the user os, because the 3 gs bin
provided here are a little heavy (~100Mb) and still no better than a proper build.

Credits
-------

Guillaume Potier. @guillaumepotier

License
-------

PdfXtractor is released under the MIT License for the PHP Wrapper. Unfortunately,
GhostScript is under GPL license. Which makes this whole project under GPL license.
See the bundled LICENSE file for more details.
