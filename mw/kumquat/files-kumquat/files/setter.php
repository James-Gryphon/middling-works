<?php
    // Theme section
if(!isset($_SESSION['theme']) || isset($_GET['theme']))
{
    include_once("".path."/files/themes.php");
        // a hack to make theme changes work
        if(!empty($_GET['theme']))
        {
            $_POST['theme'] = $_GET['theme'];
        }
    PrefUpdater("theme", $theme_array);
}
// generic prefs
if(!empty($_POST) && isset($_GET['a']) && $_GET['a'] === "prefs"){
}

if(isset($_POST['password']) && isset($_GET['a']) && $_GET['a'] === "account"){
    $query = $pdo->prepare("SELECT * FROM home_accounts WHERE id = :id");
    $query->execute([':id' => $_SESSION['id']]);
    $result = $query->fetch();
    if (password_verify($_POST['password'], $result['password'])){
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $errors["email"]["email_invalid"] = true;}
        if(mb_strlen($_POST['email'], "UTF-8") > 254){$errors["email"]["email_length"] = true;}
            // process request - need to make sure that username can't match email, or email username
            if($result['email'] != $_POST['email']){
            $query = $pdo->prepare("SELECT 'id, email' FROM home_accounts WHERE email = :email OR username = :email
            ");
            $query->execute(
                [
                ':email' => $_POST['email']
                ]
            );
            $email_check = $query->fetch();
            if(!empty($email_check) && $email_check['id'] != $result['id']){ $errors["email"]["email_taken"] = true;}
            if(empty($errors['email'])){ $changes['email'] = $_POST['email'];}
        }

        if(!empty($_POST['new_password'])){
        if(mb_strlen($_POST['new_password'], "UTF-8") < 12) { $errors["password"]['password_short'] = true;}
        if(empty($errors['password'])){ $changes['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);}
        }
        if(!empty($changes)):
            $query = "UPDATE `home_accounts` SET";
            foreach($changes as $key => $value){
                $query .= " $key=:$key,";
            }
            $query = substr($query, -0, -1); // omit last comma
            $query .= " WHERE id=:id";
            $sth = $pdo->prepare($query);
            foreach($changes as $key => $value){
            $change_array[":$key"] = $value;
            }
            $change_array[':id'] = $_SESSION['id'];
            $sth->execute(
                $change_array
                );
         SessionUpdater();
         $account_changed = true;
        else: $account_not_changed = true;
        endif;
} else {$errors["cur_password"]['password_bad'] = true;}
}

?>