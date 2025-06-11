<?php
$query_string = "from ult_matches WHERE status = 'completed'";
if(isset($_GET['filter']))
{
    if($_GET['filter'] === "yours")
    {
        if(isset($user_id))
        {
            $filter = $user_id;
        }
    }
    else 
    {
        $filter = (int)$_GET['filter'];
    }
}
error_log($filter);
if(isset($filter))
{
    $query_string .= " AND (host = {$filter} OR guest = {$filter})";
}
$query_string .= " ORDER BY `updated` ";
/* This is all adapted from gPress */
if(isset($_GET['dir']) && $_GET['dir'] === "on")
{
    $dir = "ASC";
}
else 
{
    $dir = "DESC";
}
$query_string .= $dir;

$total_matches = "SELECT count(*) as count $query_string";
$query = $pdo->query($total_matches);
$total_matches = $query->fetch(PDO::FETCH_ASSOC);
$total_matches = $total_matches['count'];
$max_pages = ceil($total_matches / page_count);
if(isset($_GET['p']))
{
    $intval = intval($_GET['p']);
    if($intval <= 1){ $page = 1;}
    elseif($intval > $max_pages){ $page = $max_pages;}
    else {$page = $intval;}
} else {$page = 1;}
$offset = ($page * page_count) - page_count;

$query_string .= " LIMIT $offset,"; $query_string .= page_count;

$query = $pdo->query("SELECT * {$query_string}");
$matches_postfilter = $query->fetchAll(PDO::FETCH_ASSOC);

// require("".path."/ult/temps/lobby_tpl.php");
?>
