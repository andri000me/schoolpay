<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Auth';
$route['translate_uri_dashes'] = FALSE;

$route['404_override'] = 'Auth/notfound';
$route['unauthorized'] = 'Auth/unauthorized';

$route['login'] = 'Auth/index';
$route['logout'] = 'Auth/logout';

// Payment 
	$route['Payment/complete'] 		= 'Keuangan/Midtrans/complete';
	$route['Payment/pending'] 		= 'Keuangan/Midtrans/pending';
	$route['Payment/error'] 		= 'Keuangan/Midtrans/error';
	$route['Payment/finish'] 		= 'Keuangan/Midtrans/finish';
	$route['Payment/unfinish'] 		= 'Keuangan/Midtrans/unfinish';
	$route['Payment/notification'] 	= 'Keuangan/Midtrans/notification';
// Payment 