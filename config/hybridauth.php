<?php
use Cake\Core\Configure;
//For Localhost
$config['Hybridauth'] = [
		"base_url" => BASE_URL. 'social_endpoint',
    'providers' => [
        "Facebook" => array(
		        "enabled" => true,
		        "keys" => array("id" => "906597772768923", "secret" => "4b5210d12a33e1bbdde53b79e660dda2"),
		    ),
    ],
    'debug_mode' => Configure::read('debug'),
    'debug_file' => LOGS . 'hybridauth.log',
];