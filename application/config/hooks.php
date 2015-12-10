<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'] = array(
    array(
        'class' => 'Auth_hook',
        'function' => 'check',
        'filename' => 'auth_hook.php',
        'filepath' => 'hooks'
    ),
    array(
        'class' => 'log_hook',
        'function' => 'registrar',
        'filename' => 'log_hook.php',
        'filepath' => 'hooks'
    )
);