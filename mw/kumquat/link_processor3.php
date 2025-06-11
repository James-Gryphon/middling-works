<?php
$prefs = parse_ini_file("settings.ini");
foreach($prefs as $param => $value) { define("$param", $value);}
unset($prefs);
$pdo = new PDO("mysql:host=".host.";dbname=".db.";charset=utf8mb4;", member, passcode);
$query = $pdo->query("SELECT * FROM `local_database`.`ay_to_si_list` WHERE length = 3");
$results = $query->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $key => $value)
{
	$newquery = $pdo->prepare(
		"SELECT * FROM `local_database`.`ay_to_si_list` WHERE `length` = :length AND ((`one` = :a_one AND `dos` = :a_dos) XOR (`one` = :b_one AND `tre` = :b_tre) XOR (`dos` = :c_dos AND `tre` = :c_tre))"
	);
	$newquery->execute([
	':length' => $value['length'],
        ':a_one' => $value['one'],
        ':a_dos' => $value['dos'],
	':b_one' => $value['one'],
	':b_tre' => $value['tre'],
	':c_dos' => $value['dos'],
	':c_tre' => $value['tre'],
    ]);
	$newresults = $newquery->fetchAll(PDO::FETCH_ASSOC);
	foreach($newresults as $newkey => $newvalue)
	{
		$newerquery = $pdo->prepare(
			"SELECT * FROM `local_database`.`ay_to_si_links` WHERE `first_id` = :a_id AND `second_id` = :b_id"
		);
		$newerquery->execute([
			':a_id' => $newvalue['id'],
			':b_id' => $value['id'],
		]);
			$newerresult = $newerquery->fetch(PDO::FETCH_ASSOC);
			if(empty($newerresult))
			{
				$newerquery = $pdo->prepare(
				"REPLACE INTO `ay_to_si_links` (`first_id`, `second_id`) VALUES (:first, :second)");
				$newerquery->execute([
				':first' => $value['id'],
				':second' => $newvalue['id']
				]);
			}
	}
}

?>
