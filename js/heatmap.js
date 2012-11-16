$(function(){

	/**
	 * Config
	 */
	var JSON_ENDPOINT = 'twitter.php';

	var map;

	bbc.mtk.load({

		version: "1.6",

		onLoad: function() {

			map = new bbc.mtk.OpenLayers.Map(
				'maparea',
				{
					// provider: 'virtualearth',
					onLoad: function() {
						this.setCenter(this.getLonLat(-3.2, 54.59), 5);
						updateTweets();
					}
				}
			);

		}
	});

	var markers = [];
	var tweets = {};
	var layers = [];

	function updateTweets()
	{
		$('#submitline').hide();
		$('#fetch_status').show();

		for(var i = 0; i < layers.length; i ++) {
			layers[i].display(false);
		}

		$.ajax({
			url: JSON_ENDPOINT,
			type: 'GET',
			dataType: 'json',
			data: 'service=' + $('#service').val(),

			success: function(response)
			{
				jQuery.each(response, function(i, item) {

					// Add in a little UI trickery:
					window.setTimeout(function(){
						// This is horrifically bad. Like, awful. But it seems to be the
						// only way of avoiding the clustering behaviour of MTK.
						var layer = new bbc.mtk.OpenLayers.Layer.PinPoints();
						var latFudge = 0;
						var lngFudge = 0;

						var latitude = item.lat - latFudge;
						var longitude = item.lng - lngFudge;

						var balloon = layer.addBalloon(
							map.getPoint(item.lng - lngFudge, item.lat - latFudge),
							{
								color: 'red'
							}
						);

						tweets[balloon.id] = item;
						map.addLayer(layer);
						layers.push(layer);
					}, 300 * i);

				});
			},

			error: function()
			{
				alert('Whoops, bad fetch.');
			},

			complete: function()
			{
				$('#submitline').show();
				$('#fetch_status').hide();

				countdownInProgress = true;
			}
		})
	}

	var countdownInProgress = false;
	var countdownLength   = 60;
	var countdownCurrent  = countdownLength;
	var $countdown        = $('#refresh_clock .countdown');
	var countdownInterval = window.setInterval(function(){
		if(countdownInProgress)
		{
			countdownCurrent --;
			$countdown.text(countdownCurrent);

			if(countdownCurrent == 0) {
				countdownCurrent = countdownLength;
				countdownInProgress = false;
				updateTweets();
			}
		}
	}, 1000);

	$('#serviceform').on('submit', function(e){
		e.preventDefault();
		updateTweets();
	}).submit();

});