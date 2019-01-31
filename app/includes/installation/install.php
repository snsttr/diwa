<?php
// Run installation
try {
    if(!include(INSTALLATION_PATH . '/model.php')) {
        die('Error: could not include "model.php".');
    }
}
catch(Exception $ex) {
    die('Error: could not include "model.php": ' . $ex->getMessage());
}
$installationModel = new Installation_Model($db, $config['database']['prefix']);
$installationModel->dropDiwaTables();
$installationModel->createDiwaTables();
$installationModel->insertDiwaData();