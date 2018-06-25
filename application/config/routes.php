<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
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


$route['app/vehicle/truck/add'] = 'app/vehicle/add_truck';
$route['app/vehicle/truck/edit/(:any)'] = 'app/vehicle/edit_truck/$1';
$route['app/vehicle/truck/delete/(:any)'] = 'app/vehicle/delete_truck/$1';

$route['app/vehicle/trailer/add'] = 'app/vehicle/add_trailer';
$route['app/vehicle/trailer/edit/(:any)'] = 'app/vehicle/edit_trailer/$1';
$route['app/vehicle/trailer/delete/(:any)'] = 'app/vehicle/delete_trailer/$1';

$route['app/vehicle/type/edit/(:any)'] = 'app/vehicle/edit_type/$1';

$route['app/setup/checklist/add'] = 'app/setup/checklist';
$route['app/setup/checklist/view/(:any)'] = 'app/setup/view_checklist/$1';
$route['app/setup/checklist/add'] = 'app/setup/add_checklist';
$route['app/setup/checklist/edit/(:any)'] = 'app/setup/edit_checklist/$1';
$route['app/setup/checklist/delete/(:any)'] = 'app/setup/delete_checklist/$1';
$route['app/setup/checklist/item/delete/(:any)'] = 'app/setup/delete_checklist_item/$1';
$route['app/setup/checklist/item/delete_image/(:any)'] = 'app/setup/delete_item_image/$1';

$route['app/report/daily/add'] = 'app/report/add_report';
$route['app/report/daily/edit/(:any)'] = 'app/report/edit_truck/$1';
$route['app/report/daily/delete/(:any)'] = 'app/report/delete_truck/$1';

$route['app/setup/profile/update'] = 'app/setup/profile/';

//Super Admin Panel
$route['admin/accounts/user_plan/update/(:any)'] = 'admin/accounts/update_plan/$1';
