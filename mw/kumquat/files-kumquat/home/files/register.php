<?php
// registration system
/* sections:
1. create account, and redirect to login
2. email verification form
3. initial form - show username, email, password
*/
if(isset($_SESSION['id'])){
    // You don't need to register!
    if (isset($_SESSION['loc'])){
        exit(header("Location: index.php?".$_SESSION['loc']."")); } else {
        exit(header('Location: index.php'));}
    }
$errors = array();
if(isset($_POST['sess']) && isset($_SESSION['regcode']) && $_SESSION['regcode'] == $_POST['sess'] && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']){
    // you are authenticated
    $query = $pdo->prepare("SELECT 'username' as cat from home_accounts WHERE username = ?
    UNION
    SELECT 'email' FROM home_accounts WHERE email = ?
    ");
    $query->execute([$_SESSION['temp_user'], $_SESSION['temp_email']]);
    $result = $query->fetch();
    if(!empty($result)){ foreach($result as $value){ $errors[$value] = true; }}
if(empty($errors)){
// no overlap, proceed with creation
$username = $_SESSION['temp_user'];
$email = $_SESSION['temp_email'];
$password_hash = $_SESSION['temp_password'];
if(!empty($password_hash)){$password_hash = password_hash($password_hash, PASSWORD_DEFAULT); }
$query = $pdo->prepare("INSERT INTO `home_accounts` (`username`, `email`, `password`) VALUES (:username, :email, :password)");
$query->execute(
    [
    ':username' => $username,
    ':email' => $email,
    ':password' => $password_hash,
    ]
);

// jump to login
unset($_SESSION['regcode']);
unset($_SESSION['temp_user']);
unset($_SESSION['temp_password']);
exit(header('Location: index.php?a=login&z=1'));
}
}

// Section 2. Verification
if(!empty($_POST)){
if(!isset($_POST['door']) || $_POST['door'] != $_SESSION['ses_code']){$errors['session']['bad_code'] = true;}
if(!isset($_POST['username']) || empty($_POST['username'])){ $errors["user"]["user_empty"] = true;} else {
if(mb_strlen($_POST['username'], "UTF-8") > 20){$errors["user"]["user_length"] = true;} }
if(!isset($_POST['email']) || empty($_POST['email'])){ $errors["email"]["email_empty"] = 1;} else {
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $errors["email"]["email_invalid"] = true;}
if(mb_strlen($_POST['email'], "UTF-8") > 254){$errors["email"]["email_length"] = true;} }
if(!isset($_POST['password']) || empty($_POST['password'])){$errors["password"]["password_empty"] = true;}
elseif(mb_strlen($_POST['password'], "UTF-8") < 12) { $errors["password"]['password_short'] = true;}
if(empty($errors)){
    // process request - need to make sure that username can't match email, or email username
    $query = $pdo->prepare("SELECT 'username' as cat from home_accounts WHERE username = :username OR email = :username
    UNION
    SELECT 'email' FROM home_accounts WHERE email = :email OR email = :username
    ");
    $query->execute(
        [
        ':username' => $_POST['username'],
        ':email' => $_POST['email']
        ]
    );
    $result = $query->fetch();
    if(!empty($result)){ $taken = true;}}}

if(!empty($_POST) && empty($errors)):
        if(empty($taken)){
        // there's no reason not to proceed
        require_once("".path."/files/emailer.php");
        $regcode = bin2hex(random_bytes(32));
        $_SESSION['regcode'] = $regcode;
        $_SESSION['temp_user'] = $_POST['username'];
        $_SESSION['temp_email'] = $_POST['email'];
        $_SESSION['temp_password'] = $_POST['password'];
        //Content
        $mail->addAddress("{$_POST['email']}");     //Add a recipient
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Middling Registration Verification';
        $mail->Body    =
        "Someone attempted to sign up for a Middling Works account using your email address.<br>
        Verification Code: $regcode<br>
        Enter the above code into the authentication box to verify this request. If you didn\'t make this request, ignore this email.";
        $mail->AltBody = "Someone attempted to sign up for a Middling Works account using your email address.
        Verification Code: $regcode
        Enter the above code into the authentication box to verify this request. If you didn\'t make this request, ignore this email.
        ";
        $mail->send();
    }

endif;

$local_site_meta = "Register a Middling Works website account here.";