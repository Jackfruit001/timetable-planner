<?php

// Function to fetch and parse the HTML
function fetchCourseTitles($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $html = curl_exec($ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    // Extract course titles
    $courseTitles = [];
    $nodes = $xpath->query("//div[@id='subjectItems']/a");

    foreach ($nodes as $node) {
        $courseTitles[] = [
            'title' => $node->nodeValue,
            'link' => "https://w5.ab.ust.hk" . $node->getAttribute('href')
        ];
    }

    return $courseTitles;
}

$url = 'https://w5.ab.ust.hk/wcq/cgi-bin/2410/';
$courseTitles = fetchCourseTitles($url);

// Save to JSON file
file_put_contents(__DIR__ . '/course_endpoints.json', json_encode($courseTitles, JSON_PRETTY_PRINT));

echo "Course endpoints have been saved to course_endpoints.json.\n";
?>
