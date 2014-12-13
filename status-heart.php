<?php
include '/home/pi/webhostingCheck/html-lib/simple_html_dom.php';

// ini_set('display_startup_errors',1);
// ini_set('display_errors',1);
// error_reporting(-1);

set_time_limit(0);

$data = ":traffic_light:";

// -------------------
// WEB HOSTING STATUS - HEART
// -------------------

$html = file_get_html('/home/pi/webhostingCheck/temp/WHS-cache.html');
if ($html === false) {
    $html->clear();
    unset($html);
} else {

    global $CurrentIssues;
    global $CurrentFixedIssues;
    global $PlannedIssues;
    global $PlannedFixedIssues;

    $divs = $html->find('div[class=contentbox]');

    $current = $divs[0];
    $planned = $divs[1];

    $currLi = $current->find('li');
    $plannedLi = $planned->find('li');

    $CurrentIssues = count($currLi);
    $PlannedIssues = count($plannedLi);

    $CurrentFixedIssues = 0;
    foreach ($currLi as $item) {
        $fixed = $item->find("p[class=fixed]");

        if (count($fixed) > 0) {
            $CurrentFixedIssues += 1;
        }
    }

    $PlannedFixedIssues = 0;
    foreach ($plannedLi as $item) {
        $fixed = $item->find("p[class=fixed]");

        if (count($fixed) > 0) {
            $PlannedFixedIssues += 1;
        }
    }

    if ($CurrentIssues > $CurrentFixedIssues) {
        $data = ":apple:";
    } else if ($PlannedIssues > $PlannedFixedIssues) {
        $data = ":tangerine:";
    } else {
        $data = ":green_apple:";
    }
}

echo $data;

?>

