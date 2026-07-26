<?php 

$db_config = require basePath('config/db.php');
$db = new Database(config: $db_config);

$id = $_GET['id'] ?? '';

$params = [
    'id' => $id
];

$listing = $db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

loadView(
    "listings/show",
    [
        'listing' => $listing
    ]
);
