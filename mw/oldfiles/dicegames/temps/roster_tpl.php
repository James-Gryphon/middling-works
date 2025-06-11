<div class="text_board">
<form method="GET" action="">
<input type="hidden" name="s" value="dicegames">
<input type="hidden" name="a" value="roster">
<label for="r">Roster</label>
<select name="r">
<option value="1" <?php if($_SESSION['dg_roster'] == 1): ?>selected<?php endif;?>>Classic</option>
<option value="2" <?php if($_SESSION['dg_roster'] == 2): ?>selected<?php endif;?>>Balanced</option>
<option value="3" <?php if($_SESSION['dg_roster'] == 3): ?>selected<?php endif;?>>Teams</option>
</select>
<input type="submit" value="Change Roster">
</form>
<?php
foreach($contestants as $contestant): ?>
<h4 class="banner <?=$contestant->class?>"><?=$contestant->name?></h4><p><?=$contestant->blurb?></p>
<?php endforeach; ?>