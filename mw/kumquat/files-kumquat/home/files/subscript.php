<?php
if(isset($_POST['email']) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code'])
{
    $filter = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if ($filter)
    {
        $file = unserialize(file_get_contents("home/files/subscripts.json"));
        if(empty($file)){ $file = array(); $file[] = $filter;  $e = 1;} 
        else 
        {
            $target = array_search($filter, $file);
            if($target === false)
            {
                array_push($file, $filter);
                $e = 1;
            }
            else 
            { 
                unset($file[$target]); $e = 0;
            }
        }
        $file = serialize($file);
        $fp = fopen("home/files/subscripts.json", "w");
        fwrite($fp, $file);
        fclose($fp);
    }
}
?>