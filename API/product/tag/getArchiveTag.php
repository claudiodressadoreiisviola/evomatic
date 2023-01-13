<?php
require __DIR__ . '/../../../MODEL/tag.php';
header("Content-type: application/json; charset=UTF-8");

$tag = new Tag;

$result = $tag->getArchiveTag();

echo json_encode($result);
