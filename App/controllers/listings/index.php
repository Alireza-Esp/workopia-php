<?php


use Framework\Database;

$db_config = require basePath('config/db.php');
$db = new Database(config: $db_config);

$listings = $db->query("SELECT * FROM listings LIMIT 6")->fetchAll();

loadView(
    "listings/index",
    [
        'listings' => $listings
    ]
);
