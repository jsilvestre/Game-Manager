<html>

<head>
	<title><?php echo $information['title']; ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $information['charset']; ?>" />
	<meta name="language" content="fr" />
	<meta name="author" content="<?php echo $information['author']; ?>" />
	
	<?php

	foreach($css['files'] as $cssFile) {
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.$css['src'].$cssFile.'" />';
	}	
	
	foreach($js['files'] as $jsFile) {
		echo '<script type="text/javascript" src="'.$js['src'].$jsFile.'"></script>';
	}
	
	?>
	
</head>

<body>