<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['zillapage/formsubmission'] = 'publishlandingpage/formsubmission';
$route['zillapage/getpagejson'] = 'publishlandingpage/getpagejson';
$route['zillapage/thankyou/(:any)'] = 'publishlandingpage/thankyou/$1';
$route['zillapage/getblockscss'] = 'publishlandingpage/getblockscss';
