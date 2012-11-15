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




	// var mapOptions = {
	// 	center: new google.maps.LatLng(54.59, -3.2),
	// 	zoom: 6,
	// 	mapTypeId: google.maps.MapTypeId.ROADMAP
	// };

	// var map = new google.maps.Map(document.getElementById("maparea"), mapOptions);

	var markers = [];

	function updateTweets()
	{
		// Kill all the markers:
		// for(var i = 0; i < markers.length; i ++)
		// {
		// 	markers[i].setMap(null);
		// }
		// markers = [];

		$('#submitline').hide();
		$('#fetch_status').show();

		$.ajax({
			url: JSON_ENDPOINT,
			type: 'GET',
			dataType: 'json',
			data: 'service=' + $('#service').val(),

			success: function(response)
			{
				jQuery.each(response, function(i, item) {

					// This is horrifically bad. Like, awful. But it seems to be the
					// only way of avoiding the clustering behaviour of MTK.
					var layer = new bbc.mtk.OpenLayers.Layer.PinPoints();
					var latFudge = Math.random() * 0.05;
					var lngFudge = Math.random() * 0.03;

					var latitude = item.lat - latFudge;
					var longitude = item.lng - lngFudge;

					// layer.events.register( 'click', this, function() {
     //                    console.log('CLICK EVEMT');
     //                });

					var balloon = layer.addBalloon(
						map.getPoint(item.lng - lngFudge, item.lat - latFudge),
						{
							color: 'red'
						}
					);

					// balloon.events.register('click', balloon, function(){
					// 	console.log('Baloon Click');
					// });

					map.addLayer(layer);

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
			}
		})
	}

	// $('#serviceform').on('submit', function(e){
	// 	e.preventDefault();
	// 	updateTweets();
	// }).submit();

	// // Set the polling to occur:
	// window.setTimeout(function(){
	// 	updateTweets();
	// }, 3 * 60 * 1000);


});