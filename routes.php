<?php

/* My Account */

$route->get('/', 'MyAccount::index' );
$route->post('/', 'MyAccount::index' );

$route->get('/logout', 'MyAccount::logOut' );

$route->get('/subscriptions', 'MyAccount::subscriptions' );
$route->get('/billing', 'MyAccount::billing' );

/* Account Details */

$route->get('/account-details', 'MyAccount::account' );
$route->post('/account-details/update-details', 'MyAccount::update_details' );
$route->post('/account-details/update-password', 'MyAccount::update_password' );


$route->get('/download-invoice/[invoice_id]', 'PDF::render' );

/* Change Package */

$route->get('/change_package/[subscription_id]', 'MyAccount::changePackage' );
$route->get('/cancel_package/[subscription_id]', 'MyAccount::cancelPackage' );

$route->get('/billing', 'MyAccount::billing' );

$route->get('/account-info', 'MyAccount::accountInfo' );

$route->get('/create_account/[package_name]', 'Register::step_one' );
$route->post('/create_account/[package_name]', 'Register::step_one' );

$route->get('/checkout', 'Stripe::checkout');
$route->post('/client_secret', 'Stripe::clientSecret');
$route->get('/return/[session_id]', 'Stripe::return');


$route->get('/pdf_generate/[invoice_id]','PDF::render');