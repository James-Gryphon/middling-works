<link rel="stylesheet" href="<?=$res?>?vers=<?=filemtime("$res")?>">
<a href="#most_specific" class="skip">Jump to content</a>
<a id="table_of_contents" class="skip">Table of Contents</a>
<!-- 
Buttons and labels here
-->
<input type="radio" id="first_box_checker" name="cat_buttons" class="hidden box_checker" <?php if(!isset($specific_post)): ?> checked <?php endif; ?>>
<label for="first_box_checker" class="title_header"><h2>Mailbox</h2></label>
<?php if(isset($thread)): ?>
<input type="radio" id="second_box_checker" name="cat_buttons" class="hidden box_checker" checked>
<label for="second_box_checker" class="title_header" tabindex="-1"><h2>Thread</h2></label>
<?php endif; ?>
<?php if(isset($third_level)): ?>
<input type="radio" id="third_box_checker" name="cat_buttons" class="hidden box_checker" checked>
<label for="third_box_checker" class="title_header" tabindex="-1"><h2><?=$third_level_txt?></h2></label>
<?php endif; ?>
<br>
<!-- 
Print the main_box_contents
-->
<div class="main_box_content first half_width">
<div class="con_box">
<?php
$link_prototype = "index.php?a=mail";
if($sort != "created"){$link_prototype .= "&sort=$sort";}
if(isset($mnt)){$link_prototype .= "&mnt={$mnt}";}
if(isset($yrt)){$link_prototype .= "&yrt={$yrt}";}
if(isset($_GET['dir']) && $_GET['dir'] === "on"){$link_prototype .= "&dir=on";}
$link_prototype_c = $link_prototype;
if(isset($thread)){ $link_prototype_c .= "&t={$thread['thread_id']}";}
if(!empty($main_chapter)){ $link_prototype_c .= "&c={$main_chapter['chapter_num']}";}
/* if($max_pages > 1):
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
endif; */
if(!empty($first_level_array)):
foreach($first_level_array as $cat => $value): ?>
    <div class="con_box">
    <span class='z4'><a href='<?=$link_prototype?>&t=<?=$value['thread_id']?>'><?=say($value['thread_name'])?></a></span>
    <br><span class='z3'>(messages: <?=$value['message_count']?> replies, <a href='index.php?a=mail&t=<?=$value['thread_id']?>&c=<?=$value['last_chapter']?>&last_post'>last</a> by <?=$value['last_sender_name'][0]?> @ <?=qdateBuilder($value['last_post_updated'])?></span><br>
    </div>
    <hr>
<?php endforeach; 
else:
   echo "<i>You don't have access to any threads";
   if(!empty($near_months))
   {
      echo " in this time range.";
      if(!empty($near_months['last_post_month']))
      {
         echo "<br>An older active month is <a href='index.php?a=mail&mnt={$last_post_month[1]}&yrt={$last_post_month[0]}'>{$near_months['last_post_month']}</a>";
      }
      if(!empty($near_months['next_post_month']))
      {
         echo "<br>A newer active month is <a href='index.php?a=mail&mnt={$next_post_month[1]}&yrt={$next_post_month[0]}'>{$near_months['next_post_month']}</a>";
      }
   }
   else
   {
      echo "at present. Start a new one, or wait for someone to put you in one.";
   }
   echo "</i>";
endif;
?>
<div class='new_thread'>
<form method='POST' name='new_thread_form'>
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<details>
<summary>New thread...</summary>
<span class='z3'><b><label for='new_thread'>Thread Title</label></b></span><br><input type='text' id='new_thread' name='new_thread'><br>
<span class='z3'><b><label for='nt_new_chptr'>First Chapter</label></b></span><br><input type='text' id='nt_new_chptr' name='new_chptr'><br>
<span class='z3'><b><label for='nt_invitee'>First Invitee</label></b></span><br><input type='text' id='nt_invitee' name='nt_invitee'><br>
<span class='z3'><b><label for='nt_invite_rank'>Invitee Rank</label></b></span><br>
<select name='nt_invite_rank' id='nt_invite_rank'>
<option value='-1'>Reader</option>
<option value='0' selected>Member</option>
<option value='1'>Curator</option>
<option value='2'>Operator</option>
<option value='3'>Moderator</option>
</select><br>
<span class='z3'><b><label for='nt_message'>New Message</label></b></span><br>
<textarea name="nt_message" cols=80 rows=10>
<?php if (!empty($errors["new_thread"]) && !empty($_POST['nt_message'])){echo say($_POST['nt_message']);} 
if(isset($errors["new_thread"]['message_length'])){
echo "<span class='notice_text red'>• <span class='z3'>", notice("message_length"), "</span></span><br>";
}
?>
</textarea><br>
<input type="submit" value='Send'><br>
</form>
</details>
</div></div>
<div class="sort_box"><form method="GET" name="thread_sorter"><input type='hidden' name='a' value='mail'>
<input type='number' name='mnt' min='1' max='12' value='<?php if(isset($mnt)){echo $mnt;}?>'>
<input type='number' min='2025' max='<?=$max_year?>' name='yrt' value='<?php if(isset($yrt)){echo $yrt;}?>'>
<?php

?>
<span class='z3'>sort: </span><select name="sort"><option value="created" <?php if($sort === "created"){echo "selected";} ?>>Created</option><option value="updated" <?php if($sort === "last_post_updated"){echo "selected";} ?>>Updated</option><option value="post_name" <?php if($sort === "post_name"){echo "selected";} ?>>Post Name</option></select><input type="checkbox" name="dir" id="dir" <?php if(isset($_GET['dir'])){echo "checked";}?>><label for="dir">
<span class='z3'>Reverse </span></label><input type="submit" value="Sort"></form></div>
</div>
<?php
// end first level
// second level print
if(!empty($thread)):
// Build a sorting link
if(!isset($third_level)): ?> <a id="most_specific"></a> <?php endif; ?>	
<div class="main_box_content second half_width">
<?php
$chapter_chooser = "<div class='sort_box'><a class='float_right' href='{$link_prototype_c}&m_list=true'>Memberlist</a>
<form method='GET' name='chapter_chooser'>";
$chapter_chooser .= "<input type='hidden' name='a' value='mail'>";
$chapter_chooser .= "<input type='hidden' name='t' value='{$thread['thread_id']}'>";
$chapter_chooser .= "<input type='hidden' name='mnt' value='{$mnt}'>";
$chapter_chooser .= "<input type='hidden' name='yrt' value='{$yrt}'>";

   if($page > 1){$chapter_chooser .= "<input type='hidden' name='p' value='$page'>";}
$chapter_chooser .=
   '<label for="c">Chapter</label>
   <select id="c" name="c">';
   foreach($chapters as $key => $value)
   {
    $chapter_chooser .= "<option value='{$value['chapter_num']}'";
    if($chapter == $value['chapter_num'])
   {
      $chapter_chooser .= "selected ";
   }
    $chapter_chooser .= ">{$value['chapter_num']}. "; 
    $chapter_chooser .= say($value['chapter_name']); 
    $chapter_chooser .= "</option>";
   }
   $chapter_chooser .= "</select><input type='submit' value='Read'>";
   if($thread['auth_level'] > 0){$chapter_chooser .= "<a href='{$link_prototype_c}&c_edit=true'}'>Edit</a>";}
   $chapter_chooser .= "</form></div>";
   echo $chapter_chooser;
   ?>
<?php
foreach($messages as $key => $value):
?>
<div class="con_box">
<?php
// postOnIndex(key($specific_post), $topic);
$sender = get_username($value['sender_id']); 
$string = say($value['msg_body']);
?>
<div class='meta_data'><b class='z3'><?=$value['local_msg_id']?></b> - sent by <?=$sender[0]?> <span class='z3'> at <?=qdateBuilder($value['created'])?></span>
<?php if(($sender === $_SESSION['username'] && thread['auth_level'] > 0) || $thread['auth_level'] > 1):?>
   <a href='<?=$link_prototype_c?>&e_id=<?=$value['local_msg_id']?>'>(edit)</a><?php endif; ?><br>
<?php if($value['created'] != $value['updated']): ?>
<span class='z3'>last edited <?=qdateBuilder($value['updated'])?></span>
<?php endif;
?>
</div><div class='post_body'><?=$string?></div>
</div>
<hr>
<?php
endforeach;
if($thread['auth_level'] > -1):
?>
<b>Reply</b><br>
<form method="POST" action="" id="thread_reply" name="thread_reply" autocomplete="off">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<textarea name="message" cols=80 rows=10>
<?php if (!empty($errors["new_message"]) && !empty($_POST['message'])){echo say($_POST['message']);} 
if(isset($errors["new_message"]['message_length'])){
echo "<span class='notice_text red'>• <span class='z3'>", notice("message_length"), "</span></span><br>";
}
?>
</textarea><br>
<input type="submit" value='Send'><br>
</form>
<?php
endif;
echo $chapter_chooser;
if($max_pages > 1):
    echo "<div class='page_box bottom'>$pager</div>";
endif;
?></div><?php
endif;

// chapter editor
if(isset($third_level))
: ?>
   <div class="main_box_content third">
<?php
if(isset($_GET['c_edit']) && $thread['auth_level'] > 0):
   /*
   Two basic approaches to editing chapters
   The first way is to copy the FitB puzzle editor interface
   This is more powerful and allows sweeping edits to chapters
   The other is more limited - allow people to edit, add, or delete one chapter at a time
   This is safer and perhaps more secure
   */
  echo "<form method='POST' action=''><input type='hidden' name='door' value='{$_SESSION['ses_code']}'><table class='full_width' role='presentation'>
  <tbody>
    <tr><th>Chapter Name</th><th>First Post</th><th>Last Post</th><th>Delete?</th><th><input type='submit' name='edit_chapters' value='Edit All'></th></tr>
    ";
   foreach($chapters as $key => $value)
   {
      echo "<tr><td>
      <input type='text' id='c_edit_nm_{$key}' name='c_edit_nm_{$key}' value='", say($value['chapter_name']), "'></td>
      <td><input type='number' id='c_edit_fp_{$key}' name='c_edit_fp_{$key}' value='", say($value['first_post']), "'></td>
      <td><input type='number' id='c_edit_lp_{$key}' name='c_edit_lp_{$key}' value='", say($value['last_post']), "'></td>
      <td><input type='checkbox' id='c_edit_dl_{$key}' name='c_edit_dl_{$key}'></td>
      <td></td></tr>";
   }
   echo "</form><form method='POST' action=''><input type='hidden' name='door' value='{$_SESSION['ses_code']}'><tr><td width=5><h5>New Chapter</h3></td><tr>
   <tr><th>Chapter Name</th><th>First Post</th><th>Last Post</th><th>New Chapter ID</th><th></th></tr>
   <td><input type='text' id='chapter_name' name='chapter_name'></td>
   <td><input type='number' id='first_post' name='first_post'></td>
   <td><input type='number' id='last_post' name='last_post'></td>
   <td><input type='number' id='add_chapter' name='add_chapter'></td>
   <td><input type='submit' value='Create'></td></tr>";
   echo "</tbody></table></form>";
elseif($third_level && isset($target_msg)):?>
<b>Edit Message</b><br>
<form method="POST" action="" id="edit_msg" name="edit_msg" autocomplete="off">
<input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<textarea name="e_msg" cols=80 rows=10>
<?php if (!empty($errors["e_msg"]) && !empty($_POST['e_msg']))
{
   echo say($_POST['e_msg']);
}
else
{
   echo say($target_msg['msg_body']);
}
if(isset($errors["new_message"]['message_length']))
{
   echo "<span class='notice_text red'>• <span class='z3'>", notice("message_length"), "</span></span><br>";
}
?>
</textarea><br>
<input type="submit" value='Update'><br>
</form>
<?php 
elseif(isset($_GET['m_list'])):
?>
   <table class='member_list'>
      <tbody>
         <th>Username</th>
         <th>Position</th>
<?php
foreach($members as $key => $value):
?>
<tr>
<td><?=$value['username']?></td>
<td><?=
match($value['auth_level'])
{
   -3 => "Removed",
   -2 => "Barred",
   -1 => "Reader",
   0 => "Member",
   1 => "Curator",
   2 => "Operator",
   3 => "Moderator",
}
?></td>
</tr>
<?php
endforeach;
if($thread['auth_level'] > 1):
?>
<hr>
<tr><form input method='POST' name='member_edit'><input type="hidden" name="door" value=<?=$_SESSION['ses_code']?>>
<td><input type='text' name='target_member'></td>
<td>
<select name='target_position' id='target_position'>
<option value='-3'>Removed</option>
<option value='-2'>Barred</option>
<option value='-1'>Reader</option>
<option value='0' selected>Member</option>
<option value='1'>Curator</option>
<option value='2' <?php if($thread['auth_level'] < 2){echo "disabled";}?>'>Operator</option>
<option value='3' <?php if($thread['auth_level'] < 3){echo "disabled";}?>>Moderator</option>
</select><br>
</td>
<?php
if($thread['auth_level'] > 1):
   ?>
   <td>
   <input type='submit' value='Edit'>
   <?php endif; ?>
   </td>
<?php   
endif;
?>
</tbody>
</table>
</div>
<?php
endif;
endif;



$res = "main/gpress.js";
?>
<script src="<?=$res?>?vers=<?=filemtime("$res")?>"></script>