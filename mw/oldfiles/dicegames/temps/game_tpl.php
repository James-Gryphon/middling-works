<noscript><link rel="stylesheet" href="dicegames/unhider.css"></noscript>
<center>
<label for="dicebuilder" id="buildswitch">Custom Dice</label>
<input type="checkbox" id="dicebuilder" name="dicebuilder">
<div id="customdice">
    <form name="customdiceform" method="GET"><b><span class='z3'>Custom Dice Form</span></b><br>
    <input type="hidden" name="s" value="dicegames">
    <input type="hidden" name="a" value="game">
    <label for="name">Name</label>
    <input type="text" name="name" placeholder="Unnamed Entrant" value="<?=$contestant->name?>"><br>
<?php /*   <label for="color">Color</label>
    <input type="color" name="col" placeholder="#777777" value="<?=$contestant->color?>"><br>
    <label for="color">BG Color</label>
    <input type="color" name="bg" placeholder="#f9f9f9" value="<?=$contestant->background?>"><br> */ ?>
    <label for="dc">Dice Count</label>
    <input type="number" name="dc" placeholder="1" value="<?=$contestant->dicecount?>"><br>
    <label for="sc">Side Count</label>
    <input type="number" name="sc" placeholder="4" value="<?=$contestant->sides?>"><br>
    <label for="ad">Add Mod</label>
    <input type="number" name="ad" placeholder="0" value="<?=$contestant->addmod?>"><br>
    <label for="md">Mult Mod</label>
    <input type="number" name="md" placeholder="1" value="<?=$contestant->mulmod?>"><br>
    <label for="rule">Rule</label>
    <input type="text" name="rule" placeholder="high" value="<?=$contestant->rules?>"><br>
    <label for="rint">Rint</label>
    <input type="number" name="rint" placeholder="0" value="<?=$contestant->rint?>"><br>
    <label for="sr">Surge</label>
    <input type="number" name="sr" placeholder="0" value="<?=$contestant->surge?>"><br>
    <label for="sl">Slump</label>
    <input type="number" name="sl" placeholder="0" value="<?=$contestant->slump?>"><br>
    <input type="submit" name="nw"></form></div>

<button id="iterate" value="Iterate">Iterate</button>
<div class="diceboard">
<?php
foreach($tournament as $roundkey => $roundvalue)
:
    // This is the tournament level and distinguishes between rounds
    if($roundkey === 1):
        // Show whole roster here
        // For now, let's do this the long and stupid way
        ?>
        <div class="roundbox quarters">
        <span class='z4'>Quarter-Finals</span><br>
        <div class="groupbox"><span class='z4'><u>Group 1</u></span><br>
        <div class="setbox"><b><span class='z3'>Set 1</span></b><br>
        <div class='lead <?=$contestants[0]->class?>'><?=$contestants[0]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[1]->class?>'><?=$contestants[1]->name?></div>
        </div>
        <div class="setbox"><b><span class='z3'>Set 2</span></b><br>
        <div class='lead <?=$contestants[2]->class?>'><?=$contestants[2]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[3]->class?>'><?=$contestants[3]->name?></div>
        </div>
        </div>
        <div class="groupbox"><span class='z4'><u>Group 2</u></span><br>
        <div class="setbox"><b><span class='z3'>Set 1</span></b><br>
        <div class='lead <?=$contestants[4]->class?>'><?=$contestants[4]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[5]->class?>'><?=$contestants[5]->name?></div>
        </div>
        <div class="setbox"><b><span class='z3'>Set 2</span></b><br>
        <div class='lead <?=$contestants[6]->class?>'><?=$contestants[6]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[7]->class?>'><?=$contestants[7]->name?></div>
        </div>
        </div></div>
        <?php
        echo
        "<div class='quarterbox open'>";
    elseif($roundkey === 2): ?>
        <br>
        <a id='qmatches'></a>
        <div class='groupbox hid' id='semis'><b><span class='z5'>The Semi-Finals</span></b><br><br>
        <div class="setbox"><b><u><span class='z4'>Group 1</span></u></b><br>
        <div class='lead <?=$contestants[$match_associates[2][1][1]]->class?>'><?=$contestants[$match_associates[2][1][1]]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[$match_associates[2][1][2]]->class?>'><?=$contestants[$match_associates[2][1][2]]->name?></div>
        </div>
        <div class="setbox"><b><u><span class='z4'>Group 2</span></u></b><br>
        <div class='lead <?=$contestants[$match_associates[2][2][1]]->class?>'><?=$contestants[$match_associates[2][2][1]]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[$match_associates[2][2][2]]->class?>'><?=$contestants[$match_associates[2][2][2]]->name?></div>
        </div>
        <br><br>
        <div class='semibox'>
        <?php
    elseif($roundkey === 3):
        ?>
        </div><a id='smatches'></a>
        <br><div class="groupbox hid" id="finals"><b><span class='z6'>The Ephemeral Dice Tournament Finals</span></b><br><br
        <div class="setbox">
        <div class='lead <?=$contestants[$match_associates[3][1][1]]->class?>'><?=$contestants[$match_associates[3][1][1]]->name?></div>
        <div class="lead">vs.</div>
        <div class='lead <?=$contestants[$match_associates[3][1][2]]->class?>'><?=$contestants[$match_associates[3][1][2]]->name?></div>
        <br>
        <?php
    endif;

    foreach($roundvalue as $matchkey => $matchvalue)
    :
    // This distinguishes between matches; there should also be something to keep track of divisions or conferences
        ?>
        <div class='box live'>
        <div class='boxheader'>
        <div class='lead <?=$contestants[$match_associates[$roundkey][$matchkey][1]]->class?>'><?=$contestants[$match_associates[$roundkey][$matchkey][1]]->name?></div>
        <div class='lead <?=$contestants[$match_associates[$roundkey][$matchkey][2]]->class?>'><?=$contestants[$match_associates[$roundkey][$matchkey][2]]->name?></div>
        </div>
        <div class='rollbox'>
        <?php
        foreach($matchvalue as $result):
            // These are the results from a single match.
            echo "<div class='boxcol hid'>";
            if($result[2] == 1){$wclass = $contestants[$match_associates[$roundkey][$matchkey][1]]->class;} else if ($result[2] == 2) {$wclass = $contestants[$match_associates[$roundkey][$matchkey][2]]->class;} else {$wclass = "cat";}
            echo "<div class='score";
            if($result[2] == 1){ echo " $wclass";}
            echo "'>{$result[0]}</div>
            <div class='result $wclass'";
            echo ">{$result[3]} | {$result[4]}</div>
            <div class='score";
            if($result[2] == 2){ echo " $wclass";}
            echo "'>{$result[1]}</div></div>";
        endforeach;
        echo "</div></div>";
    endforeach;
    echo "<br>";
    if($roundkey === 3):
        echo "</div>";
        echo "<a id='match7'></a><br>";
        ?> </div> <?php
        echo "<br><div class='hid' id='celebration-hall'><div class='{$winner->class}' id='celebration'>$winner->name wins the Championship!!</div>";
        ?>
        <br><table><tbody><tr><th>#</th><th>Champions</th><th>Finalists</th></tr>
        <?php foreach($_SESSION['champions'] as $key => $value): ?>
        <tr>
        <td><?=$key?></td>
        <td class='<?=$value['winner-class']?>'><?=$value['winner-name']?></td>
        <td class='<?=$value['loser-class']?>'><?=$value['loser-name']?></td>
        <?php endforeach; ?>
        </tbody></table>
        <?php echo "</div><br>";
    endif;
    echo "</div>";
endforeach;

?>
</div>

<script src="dicegames/inter.js"></script>