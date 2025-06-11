<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker">
<label for="first_box_checker" class="title_header"><h2>Match Archive</h2></label>
<br><br>
<div class="main_box_content cbox single">
<div class="mini_box">
<b>Match Archive Results</b><br>
<div class="lobby_box">

<?php

/*
The archive itself is simple, but the search engine is harder.
We need to account for the following things:
    Who played
    Who won (or lost)
    Clock (No clock, Any clock, long clock, short clock)
    First Mover rules
    OT on/off

This is important, but not a priority for 1.0.0. I may work on this feature later... I've done all this before, but it's
hard to concentrate tonight (3/28-29/25).
*/

$link_prototype = "index.php?s=ult&a=archive";
if(isset($filter)){ $link_prototype .= "&filter=$filter";}
if($sort != "created"){$link_prototype .= "&sort=$sort";}
if(isset($_GET['dir']) && $_GET['dir'] === "on"){$link_prototype .= "&dir=on";}
if($max_pages > 1):
if(!empty($specific_post)){ $link_prototype .= "&d={$specific_post['post_id']}";}
$prev_page = $page - 1;
if($prev_page <= 1){ $prev_page = false;}
$next_page = $page + 1;
if($next_page >= $max_pages){ $next_page = false;}

// Build the pager
$pager = "<form name='page_switcher' method='GET' action='"; $pager .= $link_prototype; $pager .= "'><span class='z3'><b>Pages:</b></span> ";
$pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=1'>1</a> ";
if ($prev_page !== false){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$prev_page'>$prev_page</a> "; }
$pager .= "<input type='number' name='p' id='p' value='"; $pager .= $page; $pager .= "'><input type='submit' value='Go'> ";
if ($next_page !== false){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$next_page'>$next_page</a> "; }
if($max_pages > 1){ $pager .= "<a href='"; $pager .= $link_prototype; $pager .= "&p=$max_pages'>$max_pages</a>"; }
$pager .= "</form>";
echo "<div class='page_box top'>$pager</div>";
endif;
?>

<table class="full_width">
<thead>
<tr>
<th>Host</th>
<th>Guest</th>
<th>Best of</th>
<th>Rules</th>
<th>Created</th>
<th>Ended</th>
</tr>
</thead>
<tbody>
<?php
if(!empty($matches_postfilter))
{
foreach($matches_postfilter as $match_postfilter)
    {
        $match_host = get_username($match_postfilter['host']);
        $match_guest = get_username($match_postfilter['guest']);

    echo
    "<tr><td>", $match_host[1], "</td>";
    echo "<td>", $match_guest[1], "</td>";

    echo "</td><td>{$match_postfilter['match_length']}</td><td>", RulesDisplay($match_postfilter), "</td><td>", qdateBuilder($match_postfilter['created']), "</td><td>", qdateBuilder($match_postfilter['updated']), "</td></tr>";
    }
}
else { echo "<tr><td colspan='4' class='center_text'><i>There are no completed matches that match this filter.</i></td></tr>";}
?>
</tbody></table>
</div>
</div>
</tbody></table>
</div>
<?php
// $res = "ult/lobby.js"; 
?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>
