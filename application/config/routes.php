<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// AUTH ROUTE
$route['login'] = 'authentication/login';
$route['authenticate'] = 'authentication/authenticate';
$route['logout'] = 'authentication/logout';

// SUPERADMIN ROUTE
$route['dashboard/superadmin'] = 'dashboard/superadmin';
// --- Users (CRUD via modal) ---
$route['superadmin/users']                    = 'users/index';
$route['superadmin/users/store']              = 'users/store';
$route['superadmin/users/update/(:num)']      = 'users/update/$1';
$route['superadmin/users/delete/(:num)']      = 'users/delete/$1';

// --- Products ---
$route['superadmin/products']                 = 'product/index_superadmin';
$route['superadmin/products/tambah']          = 'product/new';
$route['superadmin/products/simpan']          = 'product/simpan';
$route['superadmin/products/edit/(:num)']     = 'product/edit/$1';
$route['superadmin/products/update/(:num)']   = 'product/update/$1';
$route['superadmin/products/delete/(:num)']   = 'product/delete/$1';

// --- Transactions / Laporan ---
$route['superadmin/transactions']             = 'transactions/reports_superadmin';
$route['superadmin/transactions/reports']     = 'transactions/reports_superadmin';
$route['superadmin/transactions/export']      = 'transactions/export';

// ADMIN ROUTE
$route['kasir/dashboard'] = 'dashboard/admin';
$route['kasir/transaksi'] = 'transactions/index';
$route['kasir/transaksi/save'] = 'transactions/save';
$route['kasir/transaksi/struk'] = 'transactions/reports';
$route['kasir/produk'] = 'product/index';
$route['kasir/produk/tambah'] = 'product/new';
$route['kasir/produk/edit/(:num)'] = 'product/edit/$1';
$route['kasir/produk/save'] = 'product/save';
$route['kasir/produk/delete/(:num)'] = 'product/delete/$1';
$route['kasir/produk/update/(:num)'] = 'product/update/$1';
