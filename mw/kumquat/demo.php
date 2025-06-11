<html style="background: #000; color: #05a;">
<?php

// Now for real work.

if(isset($_GET['m']))
{ 
    $date_array = date_parse($_GET['m']);
}
if(empty($date_array)){ $date = date("Y-m"); $date_array = date_parse($date);}
else {$date = $_GET['m'];}
$max_days = cal_days_in_month(CAL_GREGORIAN, $date_array['month'], $date_array['year']);
$min_date = "{$date}-1";
$max_date = "{$date}-{$max_days}";
var_dump($min_date);
var_dump($max_date);

/*
$date = date("Y-m-d");
echo "Date: "; var_dump($date);
echo "<br>";
echo "<br>";
$var = date_parse_from_format("Y-m-d", $_GET['m']);
echo "Date_parse: "; var_dump($var);
echo "<br>";
echo "<br>";
$var = date_create_immutable_from_format("Y-m-d", $_GET['m']);
echo "Immutable: "; var_dump($var);
echo "<br>";
if(!empty($var)){ $old_date = date_format($var, "l, F j, Y");
echo "Date_format: "; var_dump($old_date); }
*/

?>
</html>