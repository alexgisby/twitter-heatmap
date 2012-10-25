<!DOCTYPE html>
<html lang="en-gb">
	<head>
		<meta content-type="utf8">
		<title>Twitter Heatmap</title>
		<link rel="stylesheet" href="styles.css" />
	</head>

	<body>

		<div id="maparea">

		</div>
		<div id="controlarea">
			<form action="" method="get" id="serviceform">
				<fieldset>
					<legend>Choose Station</legend>
					<div class="field">
						<select id="service" name="service">
							<?php
								$services = array(
									'radio1' => 'Radio 1',
									'radio2' => 'Radio 2',
									'radio3' => 'Radio 3',
									'radio4' => 'Radio 4',
									'fivelive' => '5 live',
								);

								foreach($services as $key => $label)
								{
									echo '<option value="' . $key . '" ' . (($key == 'radio1')? 'selected="selected"' : '') . '>' . $label . '</option>';
								}
							?>
						</select>
					</div>
					<div id="fetch_status" style="display: none;">
						<b>Fetching Tweets!</b>
					</div>
					<div class="field" id="submitline">
						<input type="submit" value="Update" />
					</div>
				</fieldset>
			</form>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnhKymdX5Z84WqkoCfyDXcUgMPywlOtxw&sensor=false"></script>
		<script src="heatmap.js"></script>
	</body>

</html>