<form method="GET" action="">
<input type="hidden" name="s" value="thehunt">
<input type="hidden" name="a" value="boardbuilder_real">
<label for="brd">Board</label>
<input name="brd" type="number">
<input type="submit" value="Change Board">
</form>

<?php

if(isset($_GET['brd']) && is_numeric($_GET['brd'])){ $board_id = $_GET['brd'];}
else {$board_id = 1;}
$query = $pdo->prepare("SELECT * FROM thnt_boards WHERE board_id = ?");
$query->execute([$board_id]);
$board_data = $query->fetch(PDO::FETCH_ASSOC);

if(empty($board_data)){$errors['td']['invalid_game_id'] = true;}

if(!empty($errors['td']))
:
	foreach($errors['td'] as $error => $value)
	{
		echo notice($error, "autoinline");
	}
else:

$query = $pdo->prepare(
	"SELECT * FROM thnt_continents WHERE board_id = :board_id ORDER BY `continent_id`;
	SELECT * FROM thnt_territories WHERE board_id = :board_id;
	SELECT * FROM thnt_territory_links WHERE board_id = :board_id;
	SELECT * from thnt_card_types;"
);
$query->execute(
	[
	":board_id" => "$board_id"
	]
);
// Thanks to edmondscommerce: https://stackoverflow.com/questions/21485868/php-pdo-multiple-select-query-consistently-dropping-last-rowset
$continent_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$territory_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$territory_link_data = $query->fetchAll(PDO::FETCH_ASSOC);
$query->nextRowset();
$card_types = $query->fetchAll(PDO::FETCH_ASSOC);

$board_image_array = json_decode($board_data['board_image'], true);

// Change color palette depending on the map. Use ones that match the "Colorblind Palette".
$color_palette = array(
	array( // black
		"r" => 0,
		"g" => 0,
		"b" => 0,
	),

	array( // navy
		"r" => 0,
		"g" => 0,
		"b" => 59,
	),

	array( // dark teal
		"r" => 0,
		"g" => 49,
		"b" => 49,
	),

	array( // dark green
		"r" => 0,
		"g" => 81,
		"b" => 0,
	),

	array( // brown
		"r" => 127,
		"g" => 63,
		"b" => 0,
	),

	array( // red
		"r" => 192,
		"g" => 0,
		"b" => 0,
	),

	array( // purple
		"r" => 173,
		"g" => 0,
		"b" => 173,
	),

	array( // cyan
		"r" => 0,
		"g" => 125,
		"b" => 249,
	),

	array( // light grey
		"r" => 155,
		"g" => 155,
		"b" => 155,
	),

	array( // foam green
		"r" => 0,
		"g" => 226,
		"b" => 113,
	),

	array( // chartreuse
		"r" => 123,
		"g" => 246,
		"b" => 0,
	),

	array( // sweettart purple
		"r" => 232,
		"g" => 208,
		"b" => 255,
	),

	array( // light yellow
		"r" => 255,
		"g" => 255,
		"b" => 153,
	),

	array( // white
		"r" => 255,
		"g" => 255,
		"b" => 255,
	)
);

/* A sample instruction array, used to handle maps. */
$image_instructions = array();
$image_instructions['board_image_array'] = $board_image_array;
$image_instructions['board_dir'] = "{$board_image_array['image_name']}.png";

$image_instructions['square_step'] = array();
foreach($territory_data as $key => $var)
{
	if($var['continent_id'] != 8)
	{
    $image_instructions['square_step'][$key]['r'] = $color_palette[$var['continent_id']]['r'];
    $image_instructions['square_step'][$key]['g'] = $color_palette[$var['continent_id']]['g'];
    $image_instructions['square_step'][$key]['b'] = $color_palette[$var['continent_id']]['b'];
	}
	else
	{
		$image_instructions['square_step'][$key]['r'] = $color_palette[9]['r'];
		$image_instructions['square_step'][$key]['g'] = $color_palette[9]['g'];
		$image_instructions['square_step'][$key]['b'] = $color_palette[9]['b'];	
	}
    $image_instructions['square_step'][$key]['x'] = $var['x'];
    $image_instructions['square_step'][$key]['y'] = $var['y'];
    $name = "{$var['territory_name']} ({$var['territory_id']})";
//	$name = "({$var['territory_id']})";
    $image_instructions['square_step'][$key]['name'] = $name;
}

foreach($territory_link_data as $key => $var)
{
    $image_instructions['line'][$key]['x'] = $territory_data[$var['territory_one_id']-1]['x'];
    $image_instructions['line'][$key]['y'] = $territory_data[$var['territory_one_id']-1]['y'];
    $image_instructions['line'][$key]['x2'] = $territory_data[$var['territory_two_id']-1]['x'];
    $image_instructions['line'][$key]['y2'] = $territory_data[$var['territory_two_id']-1]['y'];
    $image_instructions['line'][$key]['one_way'] = $var['one_way'];
}

unset($i);
$_SESSION['instructions'] = $image_instructions;

endif; // ends the page
?>
<div class="map_box"><img src="../../public/thehunt/boards/genmap.php"></img></div>
