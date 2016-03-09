<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$language = array("major" => 
	array("emoji" => ":bangbang:", 
		"colour" => "danger",
		"text" => "Major Outage"),
	"major_outage" => 
	array("emoji" => ":bangbang:", 
		"colour" => "danger",
		"text" => "Major Outage"),
	"degraded_performance" => 
	array("emoji" => ":warning:", 
		"colour" => "warning",
		"text" => "Degraded Performance"),
	"minor" => 
	array("emoji" => ":warning:", 
		"colour" => "warning",
		"text" => "Minor"),
	"partial_outage" => 
	array("emoji" => ":warning:", 
		"colour" => "warning",
		"text" => "Partial Outage"),
	"operational" => 
	array("emoji" => ":white_check_mark:",
		"colour" => "good",
		"text" => "Operational"),
	"none" => 
	array("emoji" => ":bulb:",
		"colour" => "good",
		"text" => ""));


$attachments = array();

//$json = file_get_contents('http://status.heartinternet.uk/index.json');
$json = file_get_contents('/home/pi/webhostingCheck/temp/HeartStatus-cache.json');

$data = json_decode($json);

$system_message = " Overal <http://status.heartinternet.uk/|Heart Status>: " . $data->status->description . " " . $language[$data->status->indicator]['emoji'];
$system = array('fallback' => $system_message,
	'color' => $language[$data->status->indicator]['colour'],
	'text' => $system_message);


$fields = array();
foreach ($data->components as $component) {
	$level = $language[$component->status];
	//$C_message = $component->name . ": " . $component->status . " " . $level['emoji'];
	$fields[] = array ('title' => $component->name,
		'value' => $level['emoji'] . " " . $level['text'],
		'short' => true);
}
$system['fields'] = $fields;
$attachments[] = $system;


foreach ($data->incidents as $incident) {
	if ($incident->status !== "completed" && $incident->status !== "resolved") {
		$level = $language[$incident->impact];
		$I_message = $incident->name . " - Impact: " . $incident->impact . " " . $level['emoji'];

		$attachments[] = array ('fallback' => $I_message,
			'color' => $level['colour'],
			"title_link" => $incident->shortlink,
			"title" => $I_message,
			"text" => $incident->incident_updates[0]->body
			);
	}
}

$token = trim(file_get_contents('/home/pi/webhostingCheck/token.txt'));

$message = array("channel" => "#hosting", "icon_url" => "https://graph.facebook.com/133198276711959/picture?height=600&width=600");
$message['attachments'] = $attachments;

$slack_msg = json_encode($message);


$ch = curl_init();                    // initiate curl
$SlackURL = "https://lingodesign.slack.com/services/hooks/incoming-webhook?token=" . $token; // where you want to post data
curl_setopt($ch, CURLOPT_URL, $SlackURL);
curl_setopt($ch, CURLOPT_POST, true);  // tell curl you want to post something
curl_setopt($ch, CURLOPT_POSTFIELDS, array('payload' => $slack_msg)); // define what you want to post
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
$output = curl_exec($ch); // execute        
curl_close($ch); // close curl handle

echo $output;

?>
