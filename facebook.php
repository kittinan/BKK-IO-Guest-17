<?php
require_once 'vendor/autoload.php';

$token = '';

$http = new \KS\HTTP\HTTP();

$names = file('./bkk_io_guest_17.txt');
$rows = [];
$count_found = 0;
foreach ($names as $index => $name) {
  $name = trim($name);
  
  $url = 'https://graph.facebook.com/search?type=user&fields=id,name,gender,is_verified&q=' . urlencode($name) . '&access_token=' . $token;
  $json = json_decode($http->get($url), true);
  
  $rows[$index] = ['name' => $name];
  
  if (empty($json) || empty($json['data'])) {
    echo "$name: Not found\n";
    usleep(rand(1000, 1000000));
    continue;
  }
  
  $data = $json['data'][0];
  $rows[$index]['facebook_id'] =  $data['id'];
  $rows[$index]['gender'] =  !empty($data['gender']) ? $data['gender'] : null;
  $rows[$index]['is_verified'] =  $data['is_verified'];
  
  echo "$name: {$rows[$index]['facebook_id']} | {$rows[$index]['gender']}\n";
  $count_found++;
  usleep(rand(1000, 1000000));
}

echo "Found: $count_found / " . count($names) . "\n";
file_put_contents('fb.txt', json_encode($rows));