<?php
// forgot password system
/* sections:
1. actually reset the password, and redirect to login
2. authentication code + 2b) new password form
3. initial form - show 'callsign' (email or password)
*/
// Section 1: Reset password, redirect to login
if(isset($_SESSION['id']))
{
    // You don't need to log in, but if you want to change your password, that can be arranged.
        exit(header("Location: index.php?a=account&z")); 
}

$errors = array();

if(isset($_POST['verify']) && isset($_SESSION['rescode']) && isset($_SESSION['temp_email']))
{
    if(!isset($_POST['new_pass']) || empty($_POST['new_pass'])){$errors["password"]["password_empty"] = true;}
    if(mb_strlen($_POST['new_pass'], "UTF-8") < 12) { $errors["password"]['password_short'] = true;}
}

if(isset($_SESSION['rescode']) && isset($_POST['verify']) && $_SESSION['rescode'] == $_POST['verify'] && empty($errors) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']):
    $password_hash = $_POST['new_pass'];
    if(!empty($password_hash)){$password_hash = password_hash($password_hash, PASSWORD_DEFAULT); }
    $query = "UPDATE `home_accounts` SET password=:password, failed_forgets=:failed_forgets, failed_logins=:failed_logins WHERE email=:email";
    $sth = $pdo->prepare($query);
    $sth->execute(
        [
        ':password' => $password_hash,
        ':failed_forgets' => "0",
        ':failed_logins' => "0",
        ':email' => $_SESSION['temp_email']
        ]
    );
    unset($_SESSION['rescode']);
    $_SESSION['reset_done'] = true;
    exit(header('Location: index.php?a=login&z=1'));
else:
// Section 2b: Authentication code form
// Rewrite this page; it's a mess
    if((isset($_POST['callsign']) && !empty($_POST['callsign'])) || isset($_POST['verify'])):
        if(isset($_SESSION['temp_email'])){ $callsign = $_SESSION['temp_email'];}
        elseif (isset($_POST['callsign'])){ $callsign = $_POST['callsign'];}
        else {$errors['callsign']['void'] = true;}
        if(empty($errors['callsign'])):
            $query = $pdo->prepare("SELECT * from home_accounts WHERE username = :callsign OR email = :callsign
            ");
            $query->execute(
                [
                ':callsign' => $callsign
                ]
            );
            $result = $query->fetch();
            if(!empty($result)){ $forgets = $result['failed_forgets']; } else {$forgets = 3;}
            if($forgets >= 3):
                unset($_SESSION['temp_email']);
                unset($_SESSION['logincode']);
                $errors['resets_locked'] = true;
            else:
                if(empty($_POST['verify']) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code'])
                {
                    $verif_sent = true;
                    // there's no reason not to proceed
                    newTrack($result['id'], "reset");
                    require_once("".path."/files/emailer.php");
                    $rescode = bin2hex(random_bytes(32));
                    $_SESSION['rescode'] = $rescode;
                    $_SESSION['temp_email'] = $result['email'];
                    //Content
                    $mail->addAddress("{$result['email']}");     //Add a recipient
                    $mail->isHTML(true);                         //Set email format to HTML
                    $mail->Subject = 'Middling Password Reset Verification';
                    $mail->Body    =
                    "Someone sent a request to reset the password for your account.<br>
                    Verification Code: $rescode<br>
                    Enter the above code into the authentication box to verify this request. If you didn\'t make this request, ignore this email.";
                    $mail->AltBody = "Someone sent a request to reset the password for your account.
                    Verification Code: $rescode
                    Enter the above code into the authentication box to verify this request. If you didn\'t make this request, ignore this email.
                    ";
                    $mail->send();
                }
                else 
                {
                    newTrack($result['id'], "resetfail");
                    $update = "UPDATE `home_accounts` SET failed_forgets = :failed_forgets WHERE email = :email";
                    $forgets += 1;
                    $sth = $pdo->prepare($update);
                    $sth->execute(
                        [
                        ":failed_forgets" => $forgets,
                        ":email" => $callsign
                        ]
                    );
                    $errors['invalid_verification'] = true;
                }
            endif;
        endif;
    endif;
endif;

$local_site_meta = "You can reset the password for a Middling Works website account at this page.";