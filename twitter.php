<?php

error_reporting(E_ALL);

/**
 * Endpoint to hit for Twitter calls.
 */
require 'keys.php';
require 'geocode.php';

date_default_timezone_set('UTC');

require 'tmhOAuth/tmhOAuth.php';
require 'tmhOAuth/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => TWITTER_CONSUMER_KEY,
  'consumer_secret' => TWITTER_CONSUMER_SECRET,
  'user_token' => TWITTER_USER_KEY,
  'user_secret' => TWITTER_USER_SECRET,
  'curl_proxy' => REITH_PROXY,
  'oauth_version' => '1.0A',
));

$service_lookups = array(
	'radio1' => '-RT radio1 OR bbcr1 OR to:bbcr1',
	'radio2' => '-RT radio2 OR bbcr2 OR to:bbcr2',
	'radio3' => '-RT radio3 OR bbcr3 OR to:bbcr3',
	'radio4' => '-RT radio4 OR bbcr4 OR to:bbcr4',
	'fivelive' => '-RT 5live OR 5live OR 5 live',
);

$query = (isset($_GET['service']) && array_key_exists($_GET['service'], $service_lookups))?
			$service_lookups[$_GET['service']] : $service_lookups['radio1'];


$p = array(
	'q' => $query,
	'geocode' => '53.59,-3.4,480km', // Limit to UK tweets
	'count' => 100
);

$cache_filename = 'tweets-cache/' . md5(json_encode($p)) . '.json';

// Check the cache first:
if(file_exists($cache_filename) && filemtime($cache_filename) >= strtotime('-3 minutes'))
{
	$response = file_get_contents($cache_filename);
}
else
{
	$tmhOAuth->request('GET', 'https://api.twitter.com/1.1/search/tweets.json', $p);
	$response = $tmhOAuth->response['response'];

	file_put_contents($cache_filename, $response);
}


$results = json_decode($response);


// --- Work out the geo-coding data -----

$result_json_arr = array();

$i = 0;
foreach($results->statuses as $tweet)
{
	// Simplest case, Tweet has geo info:
	if($tweet->geo !== null)
	{
		$result_json_arr[] = array(
			'lat' => $tweet->geo->coordinates[0],
			'lng' => $tweet->geo->coordinates[1],
			'text' => $tweet->text,
			'avatar' => $tweet->user->profile_image_url,
		);
	}
	else
	{
		// Crap, send a geo-coding request;
		$point = fetch_latlng_for_place($tweet->user->location);
		$result_json_arr[] = $point;
	}
}

header('Content-Type: application/json');
echo json_encode($result_json_arr);
