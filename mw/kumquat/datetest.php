
<?php
echo "Post: ";
var_dump($_POST);
echo "<br>";
echo "Get: ";
var_dump($_GET);
// echo "<br>";
// echo "Server: ";
// var_dump($_SERVER);
echo "<br>";
if(!empty($_POST))
{
    $date_test = $_POST['date_test'] . $_POST['time_test'];
    $date_test = date_parse($date_test);
    $date = [
        "year" => $date_test['year'], 
        "month" => $date_test['month'],
        "day" => $date_test['day'],
        "hour" => $date_test['hour'],
        "minute" => $date_test['minute']
        ];
    foreach($date as $key => $value)
    {
        if(empty($value) && $value !== 0){echo $key; unset($date); break;}
    }
            $nudate = mktime($date['hour'], $date['minute'], 0, $date['month'], $date['day'], $date['year']);
            echo $nudate;
            $fromtimestamp = getdate($nudate);
            var_dump($fromtimestamp);
}
?>

<form method="POST" action="">
<input type="date" id="date_test" name="date_test">
<input type="time" id="time_test" name="time_test">
<input type="submit">
</form>