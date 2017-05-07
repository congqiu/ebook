<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $route['default_controller'] = 'welcome';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;

$route['book/add/source/(:any)'] = 'books/addBookSource/$1';
$route['book/add/book'] = 'books/addNewBook';
$route['book/category/(:any)'] = 'books/category/$1';
$route['book/(:any)'] = 'books/book/$1';
$route['book/(:any)/(:any)'] = 'books/page/$1/$2';
$route['book/category/(:any)/(:any)'] = 'books/category/$1/$2';


$route['home'] = 'home/showUserInfo';

$route['forgot-password'] = 'home/forgotPassword';
$route['change-password/(:any)'] = 'home/changePassword/$1';

$route['home/update'] = 'home/updateUserInfo';
$route['home/avatar'] = 'home/updateUserAvatar';
$route['home/password'] = 'home/updateUserPassword';

$route['login'] = 'home/login';
$route['logout'] = 'home/logout';
$route['register'] = 'home/register';
$route['register/confirm/(:any)'] = 'home/registerConfirm/$1';


/*
ajax
 */
$route['ajax/add/bookcase/(:any)/(:any)'] = 'books/ajaxAddToBookcase';
$route['ajax/remove/bookcase/(:any)/(:any)'] = 'books/ajaxRemoveBookcase';
$route['ajax/add/bookmark/(:any)'] = 'books/ajaxAddBookmark';

$route['ajax/user/is-auto-login/(:any)'] = 'home/ajaxAutoLogin/$1';
$route['ajax/user/is-auto-bookmark/(:any)'] = 'home/ajaxAutoBookmark/$1';
$route['ajax/book/sync/(:any)/(:any)'] = 'books/ajaxGetBooksFromBookcase/$1/$2';
$route['ajax/book/update/bookcase'] = 'books/updateBookcase';


$route['default_controller'] = 'books';