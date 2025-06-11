<?php
// check index
reject_guest();
forgive_guest();

/*
Maybe modularize this in the future - set up an array of products, subscript vs license, etc, so the tpl page isn't all hardcoded?
*/

$products = 
[
    "thehunt" => 
    [
        "long_name" => "The Hunt",
        "blurb" => "Search a continent, planet, city, or other location for the Mole.",
        "price" => "10",
        "type" => "subscription",
        "bmt_micro_id" => "21786000001",
        "auth" => 
        [
            "auth_type" => "thehunt",
            "auth_level" => 0,
            "start" => 0,
            "end" => 0,
            "life" => 0
        ],
    ],
    "ult" =>
    [
        "long_name" => "Ultimate Tic-Tac-Toe",
        "blurb" => "The best human-vs-human ULT service online.",
        "price" => "20",
        "type" => "subscription",
        "bmt_micro_id" => "21786000000",
        "auth" => 
        [
            "auth_type" => "thehunt",
            "auth_level" => 0,
            "start" => 0,
            "end" => 0,
            "life" => 0
        ],
    ]
    ];

$sql = "SELECT * from home_account_auths WHERE id = ?";
$sth = $pdo->prepare($sql);
$sth->execute([$_SESSION['id']]);
$results = $sth->fetchAll(PDO::FETCH_ASSOC);
// A hardcoded design is suboptimal... though I don't expect to have an abundance of features that need auto management

for($i = 0; $i < count($results); $i++)
{
    $products[$results[$i]['auth_type']]['auth'] = $results[$i];
    $end = strtotime($products[$results[$i]['auth_type']]['auth']['end']);
    if($end < $_SESSION['last_time'])
    {
        $products[$results[$i]['auth_type']]['auth']['subactive'] = false;
    }
    else
    {
        $products[$results[$i]['auth_type']]['auth']['subactive'] = true;
    }
}

?>