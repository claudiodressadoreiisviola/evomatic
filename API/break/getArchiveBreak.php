<?php
require __DIR__ . '/../../MODEL/pause.php';
header("Content-type: application/json; charset=UTF-8");

$query = new Pause;
$result = $query->getArchiveBreak();

$archiveBreaks = array();
for ($i = 0; $i < (count($result)); $i++) {
    $archiveBreak = array(
        "id" =>  $result[$i]["id"],
        "time" => $result[$i]["time"]
    );
    array_push($archiveBreaks, $archiveBreak);
}

if (!empty($archiveBreaks)) {
    echo json_encode($archiveBreaks);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Can't find any break"]);
}