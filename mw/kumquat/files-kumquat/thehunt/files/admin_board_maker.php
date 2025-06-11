<?php
// Board viewer and editor for The Hunt
$ip = $_SERVER['REMOTE_ADDR'];
if($ip == "192.168.1.1"):
    $prefs = parse_ini_file("settings.ini");
    foreach($prefs as $param => $value) { define("$param", $value);}
    unset($prefs);
    $pdo = new PDO("mysql:host=".host.";dbname=".db."", member, passcode);
    require_once "".path."/files/functions.php";
    // List boards
    if(!isset($_GET['b'])): ?>
    <form METHOD="GET" name="board_list">
    <select name="b">
<?php
    $sql = "SELECT * FROM `thnt_boards`";
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $boards = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($boards as $board): ?>
        <option value="<?=$board['board_id']?>"><?=$board['board_name']?></option>
    <?php endforeach; ?>
    </select>
    <input type="submit" value="Edit">
    <?php else: // if $_GET['b'] is set
    

    endif; // $_GET['b'] path ended
else: // if ip is bad
    echo "Invalid credentials.";
    endif;


?>
