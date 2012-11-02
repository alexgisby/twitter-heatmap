# Twitter Heatmap

(BBC 10% time project)

Runs a query against the new Twitter Search API for Tweets about a given radio station, and then plots
their location on a Google Map.

## Features

- Will either use a Tweets location, or run a geocoding request against Google for the users location.
- Caches Tweets for 3 minutes, and geocoding indefinitely.
- Largely asthetic animation to display Tweet points
- Fudging of location data to stop multiple pins appearing on top of each other.

## Known issues

- The search API only chucks back 100 Tweets at a time.
- No real error handling
- Code is very procedural at the moment, but fast!

## To install

	git clone etc etc
	mkdir geocode-cache
	mkdir tweets-cache
	chmod 0777 geocode-cache tweets-cache
	cp keys-example.php keys.php

You'll need to fill in the keys.php file with OAuth tokens out of Twitter and Google.
Create a Twitter application, and request a single user token to get the USER_TOKEN and USER_SECRET
values. [Instructions here](https://dev.twitter.com/docs/auth/oauth/single-user-with-examples)

For the Google one, follow the [Instructions here](https://developers.google.com/maps/documentation/javascript/tutorial#api_key)

You will also need to fill in the Reith Proxy URL (without the http:// bit)