<?php

// PHP Settings
$config['php']['error_reporting']       = 0;

// SYSTEM Settings
$config['system']['hashing_algorithm']  = 'md5';

// SQLite DATABASE Settings
$config['database']['database']         = 'db.s3db';    // points to the corresponding sqlite3 database in database/<database>
$config['database']['prefix']           = 'diwa_';      // if you want to change this, you have to rename your tables manually

// SITE Settings
$config['site']['invitation_code']      = 3702; // this is the code that has to be used to successfully register
$config['site']['show_welcome_message'] = true; // show some Explanations and hints on DIWA's index Page?
$config['site']['show_footer_info']     = true; // show infos about author and links to github in the footer?

// **************************************************
// IGNORE EVERYTHING BEYOND THIS LINE!
// **************************************************

// this is required if you use DIWA on Heroku.
if(false !== getenv('CLEARDB_DATABASE_URL')) {
    $url = parse_url(false !== getenv('CLEARDB_DATABASE_URL'));
    $config['database']['host']             = $url['host'];
    $config['database']['username']         = $url['user'];
    $config['database']['password']         = $url['pass'];
    $config['database']['database']         = substr($url['path'], 1);
}