<?php
spl_autoload_register(function ($class) {
    if (strpos($class, 'AdScore\\Common\\') === 0) {
        $path = __DIR__ . '/php-common/src/' . str_replace('AdScore\\Common\\', '', $class) . '.php';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) {
            require_once $path;
        }
    }
});
?>
