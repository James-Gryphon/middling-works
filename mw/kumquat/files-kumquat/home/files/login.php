<?php
if(isset($_SESSION['id']))
{
    // You don't need to log in!
    if (isset($_SESSION['loc']))
    {
        exit(header("Location: index.php?".$_SESSION['loc']."")); 
    } 
    else 
    {
        exit(header('Location: index.php'));
    }
}

$errors = [];

if (isset($_POST['callsign']) && isset($_POST['password']) && !empty($_POST['callsign']) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']):
    $sql = "SELECT * from home_accounts WHERE username = ? OR email = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$_POST['callsign'], $_POST['callsign']]);
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $update = "UPDATE `home_accounts` SET failed_forgets=:failed_forgets, failed_logins=:failed_logins WHERE email=:callsign OR username=:callsign";
    $sth = $pdo->prepare($update);
    if (!$result) { $errors['failed_login'] = true; } 
    else {
        $failed_logins = $result['failed_logins'];
        if($failed_logins >= 3){
            $errors['too_many_fails'] = true;
        }
        elseif (password_verify($_POST['password'], $result['password'])){
            // Login succeeded
            session_regenerate_id(true);
            $_SESSION['ses_code'] = bin2hex(random_bytes(32));
            $sth->execute(
                [
                ':failed_forgets' => "0",
                ':failed_logins' => "0",
                ':callsign' => $_POST['callsign']
                ]
            );
            foreach($result as $key => $value){
                $_SESSION[$key] = $value; }
                unset($result['password']); // dump this so it won't get saved to session
                $sql = "SELECT * from home_prefs WHERE user_id = ?";
                $sth = $pdo->prepare($sql);
                $sth->execute([$result['id']]);
                $results = $sth->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $pref)
                {
                    $_SESSION[$pref['setting']] = $pref['value'];
                }
                updateTrack($_SESSION['id'], "authed");
            if (isset($_SESSION['loc'])){
            exit(header("Location: index.php?".$_SESSION['loc']."")); } else {
            exit(header('Location: index.php'));}
        }
        else {
            $failed_logins += 1;
            updateTrack($result['id'], 'failed');
            $sth->execute(
                [
                ':failed_forgets' => $result['failed_forgets'],
                ':failed_logins' => $failed_logins,
                ':callsign' => $_POST['callsign']
                ]
            ); 
            $errors['failed_login'] = true;
            }
        }
endif;
?>