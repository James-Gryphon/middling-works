<html style="background: #000; color: #a50;">
<?php
require("files/functions.php");
// Standard form page
$errors = array();
$form = array();
$form[] = array("POST", "");
$form[] = array("text", "Value string", "Name", "Label", "This is a descriptive text");
$form[] = array("password", "", "password", "Password", "Put your password in here.");
$form[] = array("number", "1", "votes", "Electrical College", "How many electrical votes are needed to win.");
$form[] = array("submit");
$test = FormBuilder($form, $errors);
echo $test;

function FormBuilder($array, $errors){
$buffer = "";
$array = array_reverse($array);
$header = array_pop($array);
$array = array_reverse($array);
$buffer .= "<form METHOD='{$header[0]}' action='{$header[1]}'>";
var_see($array, "Array");
foreach($array as $row){
	var_see($row, "Row");
    /*
    Type, Name, Value, Longname, Blurb
    If you have a blurb, you definitely have a longname
    You always need a type
    You may need a value.
    You may not need a name, but you probably do. If you need a name, you need a value.
    If you do not need a name or value, you do not need a longname (label) or blurb.
    So: type 0, name 1, value 2, longname 3, blurb 4 */
    if(isset($row[3])){$buffer .= "<label for='{$row[2]}'>{$row[3]}</label><br>";}
    $buffer .= "<input type='{$row[0]}'"; 
    if(isset($row[1])){ $buffer .= " value='{$row[1]}'";}
    if(isset($row[2])){ $buffer .= "name='{$row[2]}'";
    if(!empty($errors[$row[2]])){ $buffer .= " class='error_wrap'";}}
    $buffer .= "><br>";
    if(isset($row[4])){$buffer .= "<span class='z3'>{$row[4]}</span><br>";}
    if(isset($row[2]) && !empty($errors[$row[2]]))
    {
        foreach($errors[$row] as $error)
        {
            $buffer .= "<span class='notice_text red'>• <span class='z3'>";
            $buffer .= notice($error);
            $buffer .= "</span></span><br>";
        }
    }
}
return $buffer;
}

?>