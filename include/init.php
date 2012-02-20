<?php
error_reporting(-1);

define('CONFIG_FILE', __DIR__.'/../config.ini');

function stdout($data){
  fputs(STDOUT, $data, strlen($data));
}

function stderr($data){
  fputs(STDERR, $data, strlen($data));
}

spl_autoload_register(function($class){
  $class = strToLower(str_replace('\\', '/', $class));
  require __DIR__."/../library/{$class}.php";
});

set_exception_handler(function($e){
  stderr("Uncaught exception in [{$e->getFile()}] ");
  stderr("on line [{$e->getLine()}]\n");
  stderr($e->getMessage()."\n");
  stderr($e->getTraceAsString()."\n");

  exit(1);
});

if (!file_exists(CONFIG_FILE)){
  stderr("You must create a config.ini file in ".realpath(__DIR__.'/../')); 
  exit(1);
}

$settings = parse_ini_file(CONFIG_FILE, true);

if (!$settings){
  stderr("Failed to parse [".CONFIG_FILE."] as valid ini file");
}

return (object) $settings;

