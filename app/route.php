<?
$controllers = array();
scanFolder($controllers, '../app/controllers', '/.php/');

foreach ($controllers as $controller) {
  require_once $controller;
}

function scanFolder(&$array, $path, $filter)
{
  $files = scandir($path);

  foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;
    $file = $path . '/' . $file;

    if (preg_match($filter, $file))
      $array[] = substr($file, 7);

    if (is_dir($file))
      scanFolder($array, $file, $filter);
  }
}