
<link rel="stylesheet" href="dicegames/local.css">
<?php
    if(isset($_SESSION['theme']) && file_exists("dicegames/roster-{$_SESSION['theme']}.css")){
        echo "<link rel='stylesheet' href='dicegames/roster-{$_SESSION['theme']}.css'>"; } else { echo "<link rel='stylesheet' href='dicegames/roster-light.css'>";}        
?>
<ser>
<?php
require_once "".path."/dicegames/temps/{$action}_tpl.php";
?></div>
<footer class="local">
<center>
<span class='z3'>Roster: <?=$rostertexts['vers']?></span><br>
<form method="GET" action="">
<input type="hidden" name="s" value="dicegames">
<input type="hidden" name="a" value="game">
<label for="r">Roster</label>
<select name="r">
<option value="1" <?php if($_SESSION['dg_roster'] == 1): ?>selected<?php endif;?>>Classic</option>
<option value="2" <?php if($_SESSION['dg_roster'] == 2): ?>selected<?php endif;?>>Balanced</option>
<option value="3" <?php if($_SESSION['dg_roster'] == 3): ?>selected<?php endif;?>>Teams</option>
</select>
<input type="submit" value="Change Roster">
</form>
<span class='z3'>Version: 1.5.1</span>
</center>
</footer>
</ser>