<?php
// check index
reject_guest();
forgive_guest();

$sql = "SELECT * from home_accounts WHERE id = ?";
$sth = $pdo->prepare($sql);
$sth->execute([$_SESSION['id']]);
$result = $sth->fetch();

?>