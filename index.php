<?php
include("Finder.php");
include("Nimiq.php");

$servers = array(
	array("http://www.example.com/logfile.log", "Pikachu1"),
	array("http://www.example2.com/logfile.log", "Bulbasaur1"),
	array("http://www.example3.com/logfile.log", "Squirtle1"),
	array("http://www.example4.com/logfile.log", "Charmander1"),
);

$nimiqs = array();
foreach($servers as $server) {
	$nimiqs[] = new Nimiq($server[0], $server[1]);
}

?>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<style type="text/css">
body {
	color: #FFF;
	background: #4776E6;  /* fallback for old browsers */
	background: -webkit-linear-gradient(to bottom, #8E54E9, #4776E6);  /* Chrome 10-25, Safari 5.1-6 */
	background: linear-gradient(to bottom, #8E54E9, #4776E6); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
	height: 100%;

}
.bubble { display: inline-block; width: 350px; margin: 10px; background: #FFF; border-radius: 4px; padding: 10px; color: #000 !important; border-left: 2px solid #e5811f;}
.bubble table td { color: #000; }
.small-text { font-size: 12px; }
.header-margin { margin-bottom: 50px; }
</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="header-margin"><img src="nimiq.png"> Your NIMIQ Miners</h1>

			<?php foreach($nimiqs as $nim) : ?>
				<?php echo $nim->get_data()->display(); ?>
			<?php endforeach; ?>

			<hr>
			<?php 
			$total_hash = "";
			$total_nims = 0;
			foreach($nimiqs as $nim) {
				$total_hash += $nim->data['hashrate'];
				$total_nims += $nim->data['balance'];
			}
			echo "TOTAL HASHRATE: <strong>" .  number_format($total_hash) . " H/s</strong> - TOTAL BALANCE: <strong>" . number_format($total_nims, 2) . " NIM</strong> - TOTAL MINERS: <strong>" . count($nimiqs) . "</strong>";
			?>
		</div>
	</div>
</div>
</body>
</html>