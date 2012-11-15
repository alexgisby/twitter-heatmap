<!DOCTYPE html>
<html lang="en-gb">
	<head>
		<meta content-type="utf8">
		<title>Twitter Heatmap</title>
		<link rel="stylesheet" href="/css/styles.css" />
	</head>

	<body>

		<div id="maparea">

		</div>
		<div id="controlarea">

			<div id="refresh_clock">
				<span class="countdown">59</span>
			</div>

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

		<?php require_once 'keys.php'; ?>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://node1.bbcimg.co.uk/glow/gloader.0.1.6.js"></script>
		<script type="text/javascript" src="http://cdnedge.bbc.co.uk/mtk/maploader.js"></script>
		<script src="/js/heatmap.js"></script>
	</body>

</html>