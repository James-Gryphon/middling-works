<?php
var_dump($_POST); echo "<br>";
$i = 1;
$agent_array = array();
foreach($_POST as $post){
if(isset($_POST["agent$i"]) && !empty($_POST["agent$i"])){ $agent_array[] = $post;}
$i += 1;
}
echo "Agent Array: "; var_dump($agent_array);
if(!empty($agent_array)){include "board_builder.php"; // for now
}




?>

<form name="settings" method="POST">
<input type="text" name="game_name"><br>
<input type="submit">
</form>


Agents and names:<br>
<form name="agents" method="POST">
<input type="text" name="agent1"><br>
<input type="text" name="agent2"><br>
<input type="text" name="agent3"><br>
<input type="text" name="agent4"><br>
<input type="text" name="agent5"><br>
<input type="text" name="agent6"><br>
<input type="text" name="agent7"><br>
<input type="submit">
</form>

Select game type:<br>
<form name="gametype" method="POST">
<label for="online">Online</label>
<input type="radio" name="gametype" value="online" disabled><br>
<label for="hotseat">Hotseat</label>
<input type="radio" name="gametype" value="hotseat" checked><br>
<label for="live">Live</label>
<input type="radio" name="gametype" value="live" disabled><br>
<input type="submit">
</form>