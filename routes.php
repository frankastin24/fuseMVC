<?php

/* Admin */

$route->get('/fuse','admin->dashboard');

$route->post('/fuse/models','admin->create_model');
$route->delete('/fuse/models/[id]','admin->delete_model');
$route->patch('/fuse/models/[id]','admin->update_model');

$route->post('/fuse/controllers','admin->create_controller');