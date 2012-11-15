$(function(){

	var mapOptions = {
		center: new google.maps.LatLng(54.59, -3.2),
		zoom: 6,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById("maparea"), mapOptions);

	var markers = [];

	function updateTweets()
	{
		// Kill all the markers:
		for(var i = 0; i < markers.length; i ++)
		{
			markers[i].setMap(null);
		}
		markers = [];

		$('#submitline').hide();
		$('#fetch_status').show();

		$.ajax({
			url: 'twitter.php',
			type: 'GET',
			dataType: 'json',
			data: 'service=' + $('#service').val(),

			success: function(response)
			{
				jQuery.each(response, function(i, item){

					if(this.lat !== undefined)
					{
						var point = this;

						// Make them appear at random intervals:
						window.setTimeout(function(){

							// Fudge the lat/lng slightly to make them not appear on top 
							// of each other:

							latFudge = Math.random() * 0.05;
							lngFudge = Math.random() * 0.03;

							var latlng = new google.maps.LatLng(
								point.lat - latFudge, 
								point.lng - lngFudge
							);

							markers.push(new google.maps.Marker({
								position: latlng,
								map: map
							}));

							

						}, i * 300);
					}

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

	$('#serviceform').on('submit', function(e){
		e.preventDefault();
		updateTweets();
	}).submit();

	// Set the polling to occur:
	window.setTimeout(function(){
		updateTweets();
	}, 3 * 60 * 1000);


});