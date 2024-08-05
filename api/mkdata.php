<?php

include_once("parser.inc.php");

// Fetch course titles and URLs from the course_endpoints.json file
$courseEndpointsFile = __DIR__ . '/../scripts/course_endpoints.json';
if (!file_exists($courseEndpointsFile)) {
    die("Course endpoints file not found. Please run fetch_courses.php first.");
}
$courseTitles = json_decode(file_get_contents($courseEndpointsFile), true);
$urls = array_column($courseTitles, 'link');

$parser = new Parser();
$result = $parser->parseCoursePage($urls);

$isValidTermProvided = isset($_GET["term"]) && preg_match("/^\d{4}$/", $_GET["term"]);
$term = $isValidTermProvided ? $_GET["term"] : $result["terms"]["current"]["num"];

$courseInfo = [
    "terms" => $result["terms"],
    "lastUpdated" => date("j F, Y, g:i a")
];

foreach ($result['courses'] as $course) {
    $courseInfo[$course->code] = $course;
}

$jsonData = json_encode($courseInfo, JSON_PRETTY_PRINT);

$dataDir = __DIR__ . '/data/';
file_put_contents($dataDir . "courseInfo_${term}.json", $jsonData);
if ($term === $result["terms"]["current"]["num"]) {
    file_put_contents($dataDir . "courseInfo.json", $jsonData);
}

print "DONE";

?>
