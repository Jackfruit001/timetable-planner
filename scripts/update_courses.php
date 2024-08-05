<?php

include_once("../api/parser.inc.php");

function updateCourseInfo($term) {
    $parser = new Parser();
    $courseTitles = json_decode(file_get_contents(__DIR__ . '/course_endpoints.json'), true);
    $urls = array_column($courseTitles, 'link');
    $result = $parser->parseCoursePage($urls);

    $courseInfo = [
        "terms" => $result["terms"],
        "lastUpdated" => date("j F, Y, g:i a")
    ];

    foreach ($result['courses'] as $course) {
        $courseInfo[$course->code] = $course;
    }

    $jsonData = json_encode($courseInfo, JSON_PRETTY_PRINT);
    file_put_contents("../api/data/courseInfo_{$term}.json", $jsonData);
    file_put_contents("../api/data/courseInfo.json", $jsonData);

    echo "Courses data has been updated for term {$term}.\n";
}

$term = '2410'; // Update this to the correct term if necessary
updateCourseInfo($term);
?>
