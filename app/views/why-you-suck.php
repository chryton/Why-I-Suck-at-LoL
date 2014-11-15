<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>You suck, <?php echo $name ?>.</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			color: #999;
		}

		pre{
			width: 50%;
			margin: 0 auto;
			display: block;
		}
	</style>
</head>
<body>
	<div class="welcome">
		<h1>This is why you suck, <?php echo $name ?>.</h1>
		
		<?php if ($parsed_stats['error'] > 0): ?>
			There has been an error.
		<?php else: ?>

		<ul>
			<li>wins = <?php echo $parsed_stats['wins'] ?></li>
			<li>games = <?php echo $parsed_stats['games'] ?></li>
			<li>winrate = <?php echo ($parsed_stats['wins'] / $parsed_stats['games']) ?></li>
			<li>kda = <?php echo $parsed_stats['kda'] ?></li>
			<li>cpm = <?php echo $parsed_stats['cpm'] ?></li>
			<li>gpm = <?php echo $parsed_stats['gpm'] ?></li>
			<li>wards = <?php echo $parsed_stats['total_wards'] ?></li>
			<li>wards per match = <?php echo $parsed_stats['wpm'] ?></li>
			<li>average level = <?php echo $parsed_stats['average_level'] ?></li>
		</ul>

	</div>

	<?php endif; ?>
		<pre><?php print_r($dump); ?></pre>

</body>
</html>
