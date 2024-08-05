<?php
header("Access-Control-Allow-Origin: *");

$dataDir = __DIR__ . '/data/';

if (isset($_GET["term"]) && preg_match("/^\d{4}$/", $_GET["term"])) {
    $filePath = $dataDir . "courseInfo_" . $_GET["term"] . ".json";
    if (file_exists($filePath)) {
        $output = json_decode(file_get_contents($filePath));
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Data for the specified term not found."]);
        exit;
    }
} else {
    $filePath = $dataDir . "courseInfo.json";
    if (file_exists($filePath)) {
        $output = json_decode(file_get_contents($filePath));
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Default data not found."]);
        exit;
    }
}

echo json_encode($output);
?>
