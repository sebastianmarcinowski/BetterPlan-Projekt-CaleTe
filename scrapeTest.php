<?php
require 'src/Service/Scrape.php'; // Adjust the path as necessary
require 'config/config.php'; // Include the config file
require_once __DIR__ . '/autoload.php';
// Ensure $config is defined
if (!isset($config)) {
    die('Configuration not found.');
}

// Database connection
$dsn = $config['db_dsn'];
$username = $config['db_user'];
$password = $config['db_pass'];

try {
//    $sql = file_get_contents('C:\Users\smh2k\Desktop\ProjektNaApki\Projekt-Ca-eTe\sql\01-plan.sql'); // Adjust the path as necessary
//    $pdo->exec($sql);

    // Create an instance of ScrapeData
    $scrapeData = new \App\Service\Scrape();

    // Call the scrapeData method with the department parameter
    $departmentTable = array('WI','WE','WA','WEkon','WTMiT','WTiICH','WBiIS','WIMiM','WNoZiR','WBiHZ','WKSiR');
    foreach($departmentTable as $department){
        $scrapeData->scrapeData($department);
    }

    echo "Data scraped and inserted successfully.";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}