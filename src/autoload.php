<?php

/**
 *
 * Autoloader code from great William Durand's Geocoder lib
 * @see https://github.com/willdurand/Geocoder/blob/master/src/autoload.php
 *
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 *
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */
 spl_autoload_register(function($className) {
     $className = ltrim($className, '\\');
     if (0 != strpos($className, 'PdfXtractor')) {
         return false;
     }
     $fileName = '';
     $namespace = '';
     if ($lastNsPos = strrpos($className, '\\')) {
         $namespace = substr($className, 0, $lastNsPos);
         $className = substr($className, $lastNsPos + 1);
         $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
     }
     $fileName = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
     if (is_file($fileName)) {
         require $fileName;

         return true;
     }

     return false;
 });