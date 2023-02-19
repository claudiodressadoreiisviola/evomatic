<?php
// ERRATO -> API solo paninara
require __DIR__ . '/../../../MODEL/tag.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$tag = new Tag;

$result = $tag->getTag($parts[6]);

echo json_encode($result);
?>