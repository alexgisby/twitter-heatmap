<?php

/**
 * Quick and dirty request to reverse-geocode a location
 *
 * @return 	array 	with lat/lng keys
 */
function fetch_latlng_for_place($placename)
{
	$url = 'http://maps.googleapis.com/maps/api/geocode/json';

	$place = str_ireplace('uk', '', $placename);
	$place = trim($place, ' ,');
	$params = array(
		'address' => $place . ' UK',
		'sensor' => 'false',
	);

	$cache_file = 'geocode-cache/' . $place . '.json';

	if(file_exists($cache_file))
	{
		$response = file_get_contents($cache_file);
	}
	else
	{
		// Make the CURL request:
		$full_url = $url . '?' . http_build_query($params);
		$c = curl_init();
		curl_setopt_array($c, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_PROXY          => 'www-cache.reith.bbc.co.uk:80',
			CURLOPT_URL            => $full_url,
		));

		$response = curl_exec($c);
		file_put_contents($cache_file, $response);
	}

	$obj = json_decode($response);

	if($obj->status == 'OVER_QUERY_LIMIT' || $obj->status == 'ZERO_RESULTS')
	{
		return array();
	}
	else
	{
		return $obj->results[0]->geometry->location;
	}

}