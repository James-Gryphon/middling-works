<?php
$ip = $_SERVER['REMOTE_ADDR'];
if($ip == "192.168.0.1"):
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=middling_main", member, passcode);
require_once "files-kumquat/files/functions.php";

$post_array = $_POST;
$checks_array = array();
// An alternate technique was described by JvdBerg - "php - finding keys in an array that match a pattern"
foreach($post_array as $key => $value)
{
	if(str_starts_with($key, "edit_"))
	{
		$check = substr($key, 5);
		$checks_array[$check] = array();
		unset($post_array[$key]);
	}
}

// second pass
foreach($post_array as $key => $value)
{
	$key_string = substr($key, -10); // This is the date string
	if(isset($checks_array[$key_string])) // By this means we can make sure that the length of the string matches what we expect
	{
		$key_pref = substr($key, 0, -11);
		$checks_array[$key_string][$key_pref] = $value;
	}
	else
	{
		unset($post_array[$key]);
	}
}

foreach($checks_array as $key => $value)
{
if(empty($value)){var_see($value, "Array"); echo "Key $key was empty!<br>";} else 
	{
	// upsert
$query = $pdo->prepare("REPLACE INTO `fitb_puzzles` (`puzzle_date`, `puzzle_text`, `puzzle_clue`, `puzzle_blurb`, `puzzle_sunday`, `puzzle_repeat`, `puzzle_clone`) VALUES (:date, :text, :clue, :blurb, :sunday, :repeat, :clone)");
$query->execute(
[
':date' => $value['fitb_time'],
':text' => $value['fitb_text'],
':clue' => $value['fitb_clue'],
':blurb' => $value['fitb_blurb'],
':sunday' => $value['fitb_sunday'],
':repeat' => $value['fitb_repeat'],
':clone' => NULL,
]
);
//	$sth = $pdo->prepare("INSERT ");
	}
}

// $current_date = date_parse(date("Y-m-d"));

if(isset($_GET['y']) && isset($_GET['m'])){
$ym = $_GET['y'] . "-" . $_GET['m'];
}

if(isset($ym))
{ 
    $date_array = date_parse($ym);
}
if(empty($date_array)){ $date = date("Y-m"); $date_array = date_parse($date);}
else {$date = $ym;}
if($date_array['month'] === 1){ $prev_year = $date_array['year'] - 1; $prev_month = 12;} else {$prev_year = $date_array['year']; $prev_month = $date_array['month'] - 1;}
if($date_array['month'] === 12){ $next_year = $date_array['year'] + 1; $next_month = 1;} else {$next_year = $date_array['year']; $next_month = $date_array['month'] + 1;}
$month = $date_array['month'];
$year = $date_array['year'];

$max_days = cal_days_in_month(CAL_GREGORIAN, $date_array['month'], $date_array['year']);
$min_date = "{$date}-1";
$max_date = "{$date}-{$max_days}";

$sth = $pdo->prepare("SELECT * from `fitb_puzzles` WHERE puzzle_date >= :min_date AND puzzle_date <= :max_date ORDER BY puzzle_date LIMIT 31");
$sth->execute
([
    ":min_date" => $min_date,
    ":max_date" => $max_date
]);
$puzzle_array = $sth->fetchAll();

$sth = $pdo->query("SELECT concat(year(puzzle_date)) as 'year' FROM `fitb_puzzles` GROUP BY year(puzzle_date) ORDER BY year(puzzle_date) ASC");
$entries = $sth->fetchAll(PDO::FETCH_COLUMN);

?>
<link rel="stylesheet" href="public/files/base.css">
<link rel="stylesheet" href="public/files/beige.css">
<form method="GET" name="month_selector">
<!-- Mozilla documentation informs me that 'month' type support is poor, so we have to do this a harder way. -->
<a href="month_filter.php?y=<?=$prev_year?>&m=<?=$prev_month?>">Earlier</a>
<input type="number" name="y">
<input type="number" name="m">
<input type="submit" value="View">
<a href="month_filter.php?y=<?=$next_year?>&m=<?=$next_month?>">Later</a>
</form>
<br>
<hr>
<table>
<tbody>
<tr class='header_types'>
<th>Text</th>
<th>Clue</th>
<th>Blurb</th>
<th>Time</th>
<th>Sunday</th>
<th>Repeat</th>
<th>Clone</th>
<th>Edit?</th>
</tr>
<form method="POST" name="post">
<?php
$l = 1;
$temp_array = [];
while($l < $max_days)
{
	$temp_month = str_pad($month, 2, "0", STR_PAD_LEFT);
	$temp_day = str_pad($l, 2, "0", STR_PAD_LEFT);
	$temp_key = "{$year}-{$temp_month}-{$temp_day}";
	$temp_array[$temp_key] = [
		"puzzle_text" => "",
		"puzzle_clue" => "",
		"puzzle_blurb" => "",
		"puzzle_date" => $temp_key,
		"puzzle_sunday" => "",
		"puzzle_repeat" => "",
		"puzzle_clone" => ""
	];
	$e = strtotime($temp_key);
	$d = date("w", $e);
	if($d === 0){$temp_array[$temp_key]['puzzle_sunday'] = 0;}
	++$l;
}
foreach($puzzle_array as $key => $value)
{
	$temp_array[$value['puzzle_date']] = $value;
}

foreach($temp_array as $key => $value): ?>
		<tr>
		<td><input type="text" name="fitb_text_<?=say($key);?>" size='20' value='<?=say($value['puzzle_text']);?>'></td>
		<td><input type="text" name="fitb_clue_<?=say($key);?>" size='20' value='<?=say($value['puzzle_clue']);?>'></td>
		<td><input type="text" name="fitb_blurb_<?=say($key);?>" size='50' value='<?=say($value['puzzle_blurb']);?>'></td>
		<td><input name="fitb_time_<?=say($key);?>" type="date" size='10' value='<?=say($value['puzzle_date']);?>'></td>
		<td><input type="text" name="fitb_sunday_<?=say($key);?>" size='1' value='<?=say($value['puzzle_sunday']);?>'></td>
		<td><input type="text" name="fitb_repeat_<?=say($key);?>" size='2' value='<?=say($value['puzzle_repeat']);?>'></td>
		<td><input type="date" name="fitb_clone_<?=say($key);?>" size='10' value='<?=say($value['puzzle_clone']);?>'></td>
		<td><input type="checkbox" name="edit_<?=say($key);?>"></td>
		</tr>
		<?php
endforeach;
?>
</tbody>
</table>
<input type="submit" value="Edit">
</form>
<?php 
endif;
?>
