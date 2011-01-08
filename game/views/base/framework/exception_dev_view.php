<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<title>An error has occurred during your request</title>
	
	<style type="text/css">
	body {
		color: #000;
		font-family: Verdana, sans-serif;
		text-align: left;
		vertical-align: top; 
		background-color: #eee;
		margin: 0px;
		padding: 0px;
		font-size: 15px;	
	}

	div.cadre { 
		margin: 5% 12.5%;
		width: 75%;
		background-color: #e0e0e0;
	}

	p { margin: 15px; }

	strong {
		font-size: 20px;
	}

	a:link, a:visited, a:active {
		color: #555;
	}

	a:hover {
		text-decoration: underline;
	}	
	</style>
</head>

<body>
	<div class="cadre">
		<p>
			<?php
				if (isset($severity)) { 
					echo "<strong>Severity</strong> : ".$severity."<br />";				
				}
			?>
			<strong>Message</strong> : <?php echo $message; ?><br />
			<strong>File</strong> : <?php echo $file; ?><br />
			<strong>Line</strong> : <?php echo $line; ?><br />
			<strong>Trace</strong> : <?php echo $trace; ?><br />
		</p>	
	</div>
</body>
</html>