<?php

$route->get('/fusemvc/','welcome::index');

/* Admin */

$route->get('/fusemvc/fuse','admin->dashboard');

$route->post('/fusemvc/fuse/models','admin->create_model');
$route->delete('/fusemvc/fuse/models/[id]','admin->delete_model');
$route->patch('/fusemvc/fuse/models/[id]','admin->update_model');

$route->post('/fusemvc/fuse/controllers','admin->create_controller');