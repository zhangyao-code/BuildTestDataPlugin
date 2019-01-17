<?php
$options = getopt("f:");
$codes = $options['f'];
$codes =explode(',',$codes);
if(!empty($codes)){
    $parameters = array();
$filePath = './././app/config/plugin.php';
$parameters = file_get_contents($filePath);
$parameters = include($filePath);
$installed_plugins = $parameters['installed_plugins'];
foreach ($codes as $code){
    unset($installed_plugins[$code]);
}
$data = "<?php 
 return array (
  'active_theme_name' => 'jianmo',
  'installed_plugins' => 
  array (";
foreach ($installed_plugins as $key=>$plugins){
    $data .= "'{$key}' =>
    array (
     'code' => '{$key}',
     'version' => '{$plugins['version']}',
     'type' => 'plugin',
     'protocol' => '3',
    ),";
}
    $data.=' )'.PHP_EOL.');';
var_dump($data);
file_put_contents($filePath,$data);
}





