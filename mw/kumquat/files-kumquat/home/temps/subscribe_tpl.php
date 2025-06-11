<center>
<div class="title_header"><h3>License & Subscription Manager</h3></div><br>
<div class="main_box_content single half_width">
<div class="con_box">
<form method="post" action="https://secure.bmtmicro.com/cart">
<input type="hidden" name="CID" value="21786">
<input type="hidden" name="CLR" value="0">
<input type="hidden" name="ACTION" value="1">

<?php
foreach($products as $key => $value):
    $text = "";
?>
<div class="left"><b><?=$value['long_name']?></b><br>
<p><?=$value['blurb']?></p>
<?php
if($value['type'] === "subscription"):

    if(empty($value['auth']['notime']))
{
    if(!empty($value['auth']['end']))
    {
        $auth_end = qdateBuilder($value['auth']['end']);
    }
    else{$auth_end = "";}

    if($value['auth']['subactive'])
    { 
        $label = "Extend Subscription";
        $text = "Subscription end date: ";
        $text .= $auth_end;
    }
    else 
    {
        $label = "Subscribe"; 
        $text = "No active subscription";
        if(!empty($auth_end))
        {
            $text .= " <span class='z3'>(expired {$auth_end})</span>";
        }
    }
}
else {$text .= "Lifetime membership!"; $lifesub = 1;}
if(isset($lifesub)):
    ?>
    <input type="checkbox" disabled><label>Extend Subscription</label><br>
    Price: <s>$<?=$value['price']?></s> <b>$0</b> / year<br>
    <?=$text?><hr><hr></div>
    <?php
    else:
    ?>
    <input type="checkbox" name="PRODUCTID" value="<?=$value['bmt_micro_id']?>" id='<?=$key?>'><label for="<?=$key?>"><?=$label?></label><br>
    Price: <b>$<?=$value['price']?></b> / year<br>
    <?=$text?><hr><hr></div>
    <?php
    endif;

endif;

endforeach; ?>
<input type="submit" value="Purchase selected products">
</form>
</div>
</div>
</center>