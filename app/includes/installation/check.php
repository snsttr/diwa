<?php
if(!isset($installation) || !is_bool($installation)) {
    $installation = false;
}

if(isset($requirementsErrors) && 0 < count($requirementsErrors)) {
    $installation = true;
}

if(!$installation) {
    // Tables to check
    $sqlTablePlaceholder = '{TABLE}';
    $sqlMask = 'SELECT * FROM ' . $sqlTablePlaceholder . ';';
    $tablesToTest = array(
        'downloads',
        'posts',
        'threads',
        'users'
    );
    try {
        foreach ($tablesToTest as $table) {
            $sql = str_replace($sqlTablePlaceholder, $config['database']['prefix'] . $table, $sqlMask);
            $result = $db->query($sql)->fetchAll();
            if (false === $result || 0 === count($result)) {
                $installation = true;
                break;
            }
        }
    } catch (Exception $ex) {
        $installation = true;
    }
}

// Run installation if neccessarry
if($installation) {
    $_GET['page'] = 'installation';
}