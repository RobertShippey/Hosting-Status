<?php
include __DIR__ . '/html-lib/simple_html_dom.php';

set_time_limit(0);

// -------------------
// WEB HOSTING STATUS - FASTHOSTS
// -------------------

$data = ":traffic_light:";

$currentIssues = "http://status.fasthosts.co.uk/rss.php?type=SYSTEM_STATUS";
$currHtml = file_get_html($currentIssues);
if ($currHtml === false) {
    //error
} else {
    $currIssueFeed = $currHtml->find("item");

    if (count($currIssueFeed) > 0) {
        $data = ":apple:";
    } else {

        $plannedIssues = "http://status.fasthosts.co.uk/rss.php?type=PLANNED_MAINTENANCE";
        $planHtml = file_get_html($plannedIssues);

        if ($planHtml === false) {
            //error
        } else {
            $planIssFeed = $planHtml->find("item");

            if (count($planIssFeed) > 0) {
                $data = ":tangerine:";
            } else {
                $data = ":green_apple:";
            }
        }
        $planHtml->clear();
        unset($planHtml);
    }
}
$currHtml->clear();
unset($currHtml);

echo $data;

?>

