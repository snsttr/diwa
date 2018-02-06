<?php

// ============================
// SITE Settings
// ============================
$config['site']['invitation_code']      = 3702;         // this is the code that has to be used to successfully register
$config['site']['show_welcome_message'] = false;        // show some Explanations and hints on DIWA's index Page?
$config['site']['show_footer_info']     = false;        // show infos about author and links to github in the footer?
$config['site']['use_mail_function']    = false;        // false: saves all mails to disk only; true: also sends mails using php's mail()-function

// ============================
// PHP Settings
// ============================
$config['php']['error_reporting']       = E_ALL;            // activate or deactivate PHP Error Messages


// ONLY CHANGE SETTINGS BELOW BEFORE INSTALLATION

// ============================
// SYSTEM Settings
// ============================
$config['system']['hashing_algorithm']  = 'md5';        // specify the hashing algorithm for password hashing. Do not change this after installation.

// ============================
// DATABASE Settings
// ============================
$config['database']['driver']           = 'sqlite';                 // available options: sqlite, mysql, pgsql
$config['database']['prefix']           = 'diwa_';                  // the table prefix (e.g. "diwa" generates the table name "diwa_users"). Do not change this after installation.
$config['database']['database']         = '../database/db.s3db';    // for sqlite: Database filename; for mysql & pgsql: Database name
// additional database settings for mysql & pgsql (ignore these if you use sqlite)
$config['database']['server']           = 'localhost';              // specify Server Hostname here
$config['database']['username']         = 'username';               // database username ...
$config['database']['password']         = 'password';               // ... and password