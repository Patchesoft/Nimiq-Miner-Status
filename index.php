<?php
include("Finder.php");
include("Nimiq.php");


$server1 = new Nimiq("http://www.example.com/logfile.log");
$server2 = new Nimiq("http://www.example2.com/logfile.log");


?>

<h2>Nimiq Servers</h2>

<?php echo $server1->get_data()->display(); ?>
<?php echo $server2->get_data()->display(); ?>


<style type="text/css">
.bubble { display: inline-block; width: 400px; margin: 10px; }
</style>