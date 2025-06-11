<?php

// main library for functions

// Scanning functions

function scanadir($dir, $mtime = 0){
// like scandir, but only with actual folders/files
$txt = scandir("$dir", 1);
array_pop($txt); array_pop($txt);
if($mtime == 1){ // add mtimes
foreach($txt as $file){
$files[$file] = filemtime("$dir/$file");
}}
else { $files = $txt; // probly ought to be a better way to do this; oh well }
return $files;
}
}

function scan_inc($file, $fallback, $path){
    /* checks if file exists, and returns a good string to use for an include
    this seems preferable to having arrays of acceptable actions
    $path does most of the work here
    */
    if(isset($file) && is_readable("{$path}{$file}")){
    $dir = array("$file" => "{$path}{$file}");
    } else {
    $dir = array("$fallback" => "{$path}{$fallback}"); }
    return $dir;
    }
    
// Echoing/printing functions

function dateBuilder($timestamp, $long = false)
{ // convert timestamp to standard date - based on the old qdateBuilder
    $date = date("M-j-y-Y-D-g-i-s-A", $timestamp);
    $date = explode("-", $date);
    // Modified to include spans
    $month = "<span class='month'>{$date[0]}</span>";
    $daynum = "<span class='daynum'> {$date[1]},</span>";
    $yearshort = "<span class='syear'> '{$date[2]}</span>";
    $yearlong = "<span class='lyear'> {$date[3]}</span>";
    $daytext = "<span class='daytxt'>, {$date[4]}</span>";
    $hour = "<span class='hour'>, {$date[5]}</span>";
    $min = "<span class='min'>:{$date[6]}</span>";
    $second = "<span class='sec'>:{$date[7]}</span>";
    $ampm = "<span class='ampm'> {$date[8]}</span>";
    if ($long)
    {
        $string = "{$month}{$daynum}{$yearlong}{$daytext}{$hour}{$min}{$second}{$ampm}";
    }
    else 
    {
        $string = "{$month}{$daynum}{$yearshort}{$daytext}{$hour}{$min}{$ampm}";
    }
    return $string;
}

function qdateBuilder($timestamp, $long = false)
{ // a handler that converts MySQL 'timestamp' to standard date and passes it on
    $date = strtotime($timestamp);
    $string = dateBuilder($date, $long);
    return $string;
}

function say($var) 
{
    $var = htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    return $var;
}

function sfsay($var) 
{
    $var = htmlentities($var, ENT_QUOTES, 'UTF-8');
    return $var;
}


// Testing functions
function var_see($var, $name)
{ // thanks to Edward Yang @ Manual for <pre> idea
    echo "$name: <pre>"; var_dump($var); echo "</pre><br>";
}

// Session manager and related functions
function SessionManager() 
{
    global $pdo;

    // thanks to Google searches talking about session management/alt session management, and the PHP manual commentator showing ini_sets
    // thanks to Álvaro González for talking about ini_sets again, and session paths
    // thanks to mdibbets on PHP manual for commenting on paths
    // Thanks to Shea on StackExchange, a sanity-saver
    // thanks to tapken of the php manual, and a. dejong of same
    // thanks to alienwebguy
    session_name(sessionname);
    session_save_path(sessiondir);
    session_start();
    $time = time();
    if (isset($_SESSION['last_time']) && ($time - $_SESSION['last_time']) > sessionlength){
        // Destroy everything except for the $_SESSION['loc'], which may be helpful
        $old_loc = $_SESSION['loc'];
        if (isset($_SESSION['temp_post'])) { $temp_post = $_SESSION['temp_post']; }
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['loc'] = $old_loc;
        if(isset($temp_post)){ $_SESSION['temp_post'] = $temp_post; }
        unset($old_loc);
        if(isset($temp_post)){ unset($temp_post); }
    }
    // regenerative ID concept from Paragonie, "The Fast Track to Safe and Secure PHP Sessions"
    if(!isset($_SESSION['conure']))
    { 
        session_regenerate_id(true);
        $_SESSION['conure'] = $time;
        $_SESSION['ses_code'] = bin2hex(random_bytes(32));
    }
    if ($_SESSION['conure'] < $time - 300)
    {
        $door = false;
        if(isset($_POST['door']) && isset($_SESSION['ses_code']) && $_POST['door'] == $_SESSION['ses_code'])
        {
            $door = true;
        }
        session_regenerate_id(true);
        $_SESSION['conure'] = $time;
        $_SESSION['ses_code'] = bin2hex(random_bytes(32));
        if($door === true){ $_POST['door'] = $_SESSION['ses_code'];}
    }
    $_SESSION['last_time'] = $time;

    if(isset($_SESSION['id']))
    {
        $sql = "UPDATE `home_accounts` SET seen_time=:seen_time WHERE id=:id";
        $sth = $pdo->prepare($sql);
        // thanks to Deepu, etc. for informing me about MySQL's odd behavior of timestamps not actually being timestamps
        // thanks for php manual for proper timestamp format
        // thanks to Code Redirect community for telling me to persist with timestamps
        
        $date = date("Y-m-d H:i:s");
        $sth->execute(
            [
            ':seen_time' => $date,
            ':id' => $_SESSION['id']
            ]
        );
        updateTrack("visited", $time);
    }
}

function updateTrack($id, $type, $time = "")
{
    global $pdo;
    if(empty($time)){ $time = time(); }
    /* The tracking function
    This is used by the session manager on a regular basis, but it's also used by the login, etc. system.
    If anyone attempts to do anything with logins, for good or bad, it should be called upon. This way we can make sure all logins are what they should be.
    There are several types:
    1. "visited": used when someone is already logged in; this shows the last time they did something
    2. "authed": used when someone logs in
    3. "failed": used when a login attempt is denied
    4. "reset": password reset initiated
    5. "resetfail": password auth code failed
    */
    // ip tracking
    $ip = $_SERVER['REMOTE_ADDR'];
    /* We use replace for this; if we wanted the upsert, which is better in some cases 
    (doesn't trigger auto-inc, and doesn't delete everything not in the values) it'd be:
        INSERT INTO `home_tracks` (`id`, `ip`) VALUES (6, '76.233.72.221') 
        ON DUPLICATE KEY UPDATE `track_time` = CURRENT_TIMESTAMP;
    */
    $query = $pdo->prepare("REPLACE INTO `home_tracks` (`id`, `ip`, `type`) VALUES (:id, :ip, :type)");
    $query->execute(
    [
    ':id' => $id,
    ':ip' => $ip,
    ':type' => $type,
    ]
    );
}

function newTrack($id, $type, $time = "")
{
    global $pdo;
    if(empty($time)){ $time = time(); }
    /* The tracking function
    This is used by the session manager on a regular basis, but it's also used by the login, etc. system.
    If anyone attempts to do anything with logins, for good or bad, it should be called upon. This way we can make sure all logins are what they should be.
    There are several types:
    1. "visited": used when someone is already logged in; this shows the last time they did something
    2. "authed": used when someone logs in
    3. "failed": used when a login attempt is denied
    4. "reset": password reset initiated
    5. "resetfail": password auth code failed
    */
    // ip tracking
    $ip = $_SERVER['REMOTE_ADDR'];
    /* We used to use replace for this (instead of insert); if we wanted the upsert, which is better in some cases (doesn't trigger auto-inc) it'd be:
        INSERT INTO `home_tracks` (`id`, `ip`) VALUES (6, '76.233.72.221') 
        ON DUPLICATE KEY UPDATE `track_time` = CURRENT_TIMESTAMP;
    */
    $query = $pdo->prepare("INSERT INTO `home_tracks` (`id`, `ip`, `type`) VALUES (:id, :ip, :type)");
    $query->execute(
    [
    ':id' => $id,
    ':ip' => $ip,
    ':type' => $type,
    ]
    );
}

function authenticate($password)
{ 
    // for if you are already logged in
    global $pdo;
    if (isset($_SESSION['id']) && isset($password)) {
        $sql = "SELECT password from home_accounts WHERE id = ?";
        $sth = $pdo->prepare($sql);
        $sth->execute([$_SESSION['id']]);
        $results = $sth->fetch();
        if (password_verify($password, $results['password'])){
        return 1; } else { return 0; }
        } else { return 0;}
}

function PrefUpdater($setting, $acceptable_values){
    global $pdo;
// This next section should be functionalized
if (isset($_POST[$setting]) && array_key_exists($_POST[$setting], $acceptable_values) && ((isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']) || $setting === "theme")){
    $value = $_POST[$setting];
    if(isset($_SESSION['id'])){
        $sql = "SELECT * from home_prefs WHERE user_id = ? AND setting = ?";
        $sth = $pdo->prepare($sql);
        $sth->execute([$_SESSION['id'], $setting]);
        $result = $sth->fetch();
        if(!empty($result)){
        $sql = "UPDATE `home_prefs` SET value=:value WHERE user_id=:user_id AND setting=:setting";
        $sth = $pdo->prepare($sql);
        $sth->execute([':user_id' => $_SESSION['id'], ':setting' => $setting, ':value' => $value]);
        } else {
        $sql = $pdo->prepare("INSERT INTO `home_prefs` (`user_id`, `setting`, `value`) VALUES (:user_id, :setting, :value)");
        $sql->execute([':user_id' => $_SESSION['id'], ':setting' => $setting, ':value' => $value]);
        }
    }
        $_SESSION[$setting] = $_POST[$setting];
    } elseif (!isset($_SESSION[$setting])) {$_SESSION[$setting] = array_key_first($acceptable_values);}
    // end here
}

function SessionUpdater()
{
global $pdo;
    $temp_id = $_SESSION['id'];
    $sql = "SELECT * from home_accounts WHERE id = ?";
    $sth = $pdo->prepare($sql);
    $sth->execute([$temp_id]);
    $results = $sth->fetch();
    foreach($results as $key => $value)
    {
        $_SESSION[$key] = $value; 
    }
    unset($_SESSION['password']); 
    // dump this so it will not get saved to session
}
        
function check_index() 
{
    if (!defined('host')){header('Location: index.php');}
}
        
function reject_guest() 
{
    // First save anything that they might have been doing, just in case they were logged in but lost it.
    if (!isset($_SESSION['id'])) 
    {
        if(isset($_POST)){ $_SESSION['temp_post'] = $_POST;}
        exit(header('Location: index.php?a=login&z'));
    }
}
        
function forgive_guest()
{
    if (isset($_SESSION['temp_post'])){ $_POST = $_SESSION['temp_post']; unset($_SESSION['temp_post']);}
}

function prevent_guests() 
{
// The same as the reject_guest and forgive_guest functions, combined into one
// First save anything that they might have been doing, just in case they were logged in but lost it.
    if (!isset($_SESSION['id'])) 
    {
        if(isset($_POST)){ $_SESSION['temp_post'] = $_POST;}
        exit(header('Location: index.php?a=login&z'));
    }
    if (isset($_SESSION['temp_post']))
    { 
        $_POST = $_SESSION['temp_post']; unset($_SESSION['temp_post']);
    }
}

function access_check($req_level, $perm_level) 
{
    if(($perm_level ?? 0) >= $req_level) { return 1;} else { return 0;}
}

function auth_check($auth_type)
{
    global $pdo;
    if(!empty($_SESSION['id']))
    {
        $query = "SELECT auth_level from home_account_auths WHERE auth_type = ? AND id = ? AND (end > ? OR notime = 1)";
        $sth = $pdo->prepare($query);
        $sth->execute([$auth_type, $_SESSION['id'], $_SESSION['last_time']]);
        $result = $sth->fetch();
        if(!empty($result))
        {
            return $result['auth_level'];
        }
        else 
        {
            return 0;
        }
    }
    else 
    {
        return -1;
    }
}

function get_userid($username)
{
    global $pdo;
    if(isset($username))
    {
        $sql = "SELECT `id` from home_accounts WHERE `username` = ?";
        $sth = $pdo->prepare($sql);
        $sth->execute([$username]);
        $result = $sth->fetch();
        if(!empty($result))
        {
            return $result['id'];
        }
        else 
        {
            return NULL;
        }
    }
    return NULL;
}

function get_username($user_id, $auth = false)
{
    global $pdo;
    if (isset($user_id))
    {
        if($auth != false)
        { // This is used for the 'add a star to the name' thing
            $sql = "SELECT `t1`.`username`, `t2`.* FROM `home_accounts` as `t1` LEFT JOIN `home_account_auths` as `t2` ON `t1`.`id` = `t2`.`id` WHERE `t1`.`id` = ?";
            $sth = $pdo->prepare($sql);
            $sth->execute([$user_id]);
            $results = $sth->fetch();
        if(!empty($results))
        {
            $result[0] = say($results['username']);
            $result[1] = $result[0];
            if($results['end'] > $_SESSION['last_time'] && $results['auth_level'] > 0)
            {
                $result[1] .= "<img src='stuff/star.svg'>";
            }
            return $result; // 0 is starless, 1 has a star
        }
        else { return false; }
        }
        $sql = "SELECT username from home_accounts WHERE id = ?";
        $sth = $pdo->prepare($sql);
        $sth->execute([$user_id]);
        $results = $sth->fetch();
    if(!empty($results))
    {
        $result[0] = say($results['username']);
        $result[1] = $result[0];
        // No stars here; both are the same
    return $result;
    }
    return false;
    }
    return false;
}

function notice($notice, $type = "inline"){
$notice = match($notice){
    'saved_work' => array("Work Saved", "Your data entry has been saved and will be completed after logging in.", "green"),
    'invalid_id_search' => array("Invalid ID", "The ID you provided does not correspond to an entry.", "yellow"),
    'weak_access' => array("Insufficient Authority", "You don't have authority to perform this action.", "red"),
    'account_exists' => array("Account Exists", "Unfortunately, there is already an account with this username in the database.", "yellow"),
    'invalid_cred' => array("Invalid Credentials", "Either this account doesn't exist, or you used an invalid password.", "yellow"),
    'insufficient_access' => array("Insufficient Access", "You do not have permission to perform this action.", "yellow"),
    'user_empty' => array("No Username Provided", "The username field was left blank, or was otherwise invalid.", "yellow"),
    'user_length' => array("Username Too Long", "The username provided was too long. The limit is 20 characters.", "yellow"),
    'email_empty' => array("No Email Provided", "The email field was left blank.", "yellow"),
    'email_invalid' => array("Invalid Email Address", "The email provided is not a valid email address.", "yellow"),
    'email_length' => array("Email Address Too Long", "The email address provided is abnormally long. The limit is 254 characters.", "yellow"),
    'password_empty' => array("No Password Provided", "The password field was left blank.", "yellow"),
    'password_short' => array("Password Too Short", "Passwords on this site must be 12 characters or longer.", "yellow"),
    'password_bad' => array("Invalid Password", "The password provided is not the correct password for this account.", "yellow"),
    'failed_login' => array("Failed Authentication", "Either you failed to enter the correct password for this account, or this account does not exist.", "yellow"),
    'login_lockout' => array("Login Lockdown", "This account's ability to log in via password has been temporarily suspended, due to repeated failed attempts. Try again later, or login via email.", "red"),
    'forgot_lockout' => array("Email Login Lockdown", "This account's ability to log in via email has been temporarily suspended, due to repeated failed attempts. Try again later, or contact the webmaster.", "red"),
    'too_many_fails' => array("Login Lockdown", "This account's ability to log in has been temporarily suspended, due to repeated failed attempts. Try again later, reset your password, or contact the webmaster.", "red"),
    'resets_locked' => array("Reset Lockdown", "The ability to reset this account's password has been temporarily suspended, due to repeated failed attempts. Try again later, or contact the webmaster.", "red"),
    'failed_verification' => array("Failed Verification", "The verification code you submitted was invalid. Recheck the most recent email that was sent to you and try again.", "yellow"),
    'invalid_batch_choice' => array("Invalid Batch Chosen", "This batch doesn't exist.", "yellow"),
    'invalid_thread_choice' => array("Invalid Thread Chosen", "Either this thread doesn't exist, or you don't have access to it.", "yellow"),
    'failed_authentication' => array("Invalid Credentials", "Either this user doesn't exist, or the password is invalid.", "yellow"),
    'account_present' => array("Account Exists", "You have a different account registered with this batch.", "yellow"),
    'count_met' => array("No New Registrations", "This batch has reached capacity and isn't accepting new registrations.", "yellow"),
    'username_taken' => array("Username Exists", "This username is already registered to an account.", "yellow"),
    'message_length' => array("Message Too Long", "The message provided was too long. The limit is 20,000 characters.", "yellow"),
    'user_id_override' => array("Global Account Override", "The local username or password are invalid, but the global account you're logged in as was recognized.", "green"),
    'email_taken' => array("Email Exists", "This email address is already registered to an account.", "yellow"),
    'invalid_number' => array("Invalid Number", "The number you typed is not one less or greater than the current number.", "yellow"),
    'you_played_last' => array("You Moved Last", "Either you or someone from your location moved last. You can't move again until someone else does.", "yellow"),
    'ip_address_taken' => array("Global Login Required", "To play from this location, you must log into the global account associated with it.", "red"),
    'invalid_game_id' => array("Invalid Game ID", "There is no game registered under the ID that you provided.", "yellow"),
    'bad_id' => array("Invalid ID Format", "The ID you provided was blank or otherwise malformed.", "yellow"),
    'title_length' => array("Title Too Long", "The title provided was too long. The limit is 40 characters.", "yellow"),
    // FitB errors
    'nothing_happens' => array("Nothing", "Nothing was entered, so nothing happens.", "yellow"),
    'fitb_space_error' => array("Space Error", "The spaces aren't in their proper places.", "yellow"),
    'fitb_length_fail' => array("Length Error", "It doesn't have the same number of characters as the puzzle.", "yellow"),
    'fitb_letter_fail' => array("Unused Letter(s)", "It contains at least one letter that is known to be unused.", "yellow"),
    'fitb_overlap_fail' => array("Overlap Fail", "Known letters aren't in their proper places.", "yellow"),
    'fitb_guessed_already' => array("Guessed Already", "It contains a letter that has already been guessed in a blank space it is known not to be in.", "yellow"),
    // ULT-like errors
    'bad_invite' => array("No Such Name", "The username you provided is not registered in the database.", "yellow"),
    // Mail errors
    'bad_first_post' => array("Bad First Post Number", "The first post number is either not a number, or it is too small or too large.", "yellow"),
    'bad_last_post' => array("Bad Last Post Number", "The last post number is either not a number, or it is too small or too large.", "yellow"),
    default => array("Unidentified Error", "An unidentified error has occurred. Please contact the webmaster and inform him of what you were doing when this problem occurred.", "red")
};
if($type == "box")
{
    $notice = "<div class='notice_container $notice[2]'><div class='notice_header' $notice[2]>$notice[0]</div><div class='notice_box'>$notice[1]</div></div>";
} 
elseif($type == "autoinline")
{
    $notice = "<span class='notice_text $notice[2]'>• <span class='z3'>$notice[1]</span></span><br>";
}
else 
{
    $notice = "$notice[1]";
}
return $notice;
}

function FormBuilder($array, $errors)
{
    $buffer = "";
    $array = array_reverse($array);
    $header = array_pop($array);
    $array = array_reverse($array);
    $buffer .= "<form METHOD='{$header[0]}' action='{$header[1]}'><input type='hidden' name='door' value='{$_SESSION['ses_code']}'>";
    foreach($array as $row){
        /*
        type 0, value 1, name 2, longname/label 3, blurb 4 */
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
    
function rand_shuffle($a)
{
    // To shuffle an array $a of $n elements (indices 0..$n-1)
    $n = count($a);
    for ($i = $n - 1; $i >= 1; $i--)
    {
        $j = random_int(0, $i);
        $ai = $a[$i];
        $a[$i] = $a[$j];
        $a[$j] = $ai;
    }
    return $a;
}

function primeFinder($m)
{ // Laboriously gets primes up to $m
$primes = [2];
$prime = $primes[0] + 1;
while ($prime < $m)
{
    $fail = 0;
    foreach($primes as $value)
    {
        $mod = $prime % $value;
        if($mod == 0){$fail = 1; break;}
    }
    if($fail == 0){$primes[] = $prime;}
    $prime += 2;
}
return $primes;
}
    
function var_swap(&$var1, &$var2)
{ // This happens to be identical to the example in the PHP manual, "New features" and Migrating from 7.0 to 7.1.x
// I did the temp_var on my own, hence my idea to make a function that did this, but then I found they had
// a function example that did the same thing - the ampersands were their idea
    $temp_var = $var1;
    $var1 = $var2;
    $var2 = $temp_var;
}

function dec_counter($number)
{ // Used to count the number of decimal digits in a number
    $d = strpos($number, ".");
    $l = strlen($number);
    $r = $l - $d - 1;
    return $r;
}

function int_counter($number)
{ // Used to count the whole-int digits in a number
    $d = strpos($number, ".");
    $r = $d - 1;
    return $r;
}

function us_met_rounder($number, $number2)
{ 
// Used to provide US government-approved rounded numbers, when converting
// from the inch-pound system to the metric, or vice versa
    $temp_var = trim($number, "0");
    $temp_var2 = trim($number2, "0");
    $temp_var_d = dec_counter($temp_var);
    $temp_var = str_replace(".", "", $temp_var);
    $temp_var2 = str_replace(".", "", $temp_var2);
    $temp_var_sub = substr($temp_var, 0, 1);
    $temp_var2_sub = substr($temp_var2, 0, 1);
    if($temp_var2_sub >= $temp_var_sub)
    {
        $result = round($number2, $temp_var_d);
        echo "us_met_rounder1: "; var_dump($temp_var_d); echo "<br>";
        echo "result: {$result}<br>";
    }
    else
    {
        $result = round($number2, $temp_var_d + 1);
        echo "us_met_rounder2: "; var_dump($temp_var_d); echo "<br>";
        echo "result: {$result}<br>";
    }
    return $result;
}

function comp_rounder($num)
{
    $dec_loc = strpos($num, ".");
    if($dec_loc === false){ return $num;}
    $temp_num = str_replace(".", "", $num);
    $temp_length = strlen($temp_num);
    $whole_digits = $dec_loc - 1;
    if($whole_digits < 3)
    {
        $result = substr($temp_num, 0, 3);
        $temp_length = 3;
    }
    else
    {
        $result = substr($temp_num, 0, $whole_digits);
        $temp_length = $whole_digits;
    }
    if($dec_loc < $temp_length)
    {
        $result = substr_replace($result, ".", $dec_loc, 0);
    }
    return $result;
}

function post_num_protector($post)
{
	$post_array = $_POST;
	$checks_array = array();
	foreach($post_array as $key => $value)
	{
		$key_string = substr($key, -10);
		if(isset($checks_array[$key_string]))
		{
			$key_pref = substr($key, 0, -11);
			$checks_array[$key_string][$key_pref] = $value;
		}
		else
		{
			unset($post_array[$key]);
		}
	}
//
}

function accountNameDisplayer($user_id)
{
    $username = say(get_username($user_id));
    $username = "$username<img src='stuff/star.svg'>";
    return $username;
}

function cli_parser($command)
{
global $debug;
/* A key function for the development of the CLI-friendly version of the site.
This converts $_POST-generated commands into $_GET strings or $_POST vars, depending on what is needed.

How is this different from the old setup?
In the traditional setup, there are broadly three levels:
s = site - chooses a site feature
a = action - covers a general category of functionality for that feature; for TH, "lobby" covers all the matchmaking, "play" covers all the game
and also a variety of preferences, like gpress's 't', which provide fine details for actions
z means a hidden action that doesn't show up in history.

It is advantageous to keep this as much as possible, for the sake of linking and history, as well as not rewriting more of the site at a time than needed. I might eventually rewrite all the site but I don't want to have to commit to doing it all at once!

For CLI, we don't normally work directly with s. When we do, it should be handled via 'feature'.

Generally, commands correspond most closely to actions. The scope (s - site, or feature) is read from the get... OR IS IT?

Features:
home
gp (gpress)
th (the hunt)
fitb
ult
plusminus
sm
maths

Two types of commands:

Current target commands:
#1) feature (feat):
#chooses a feature to run, with its own commands
2) help - provide a list of commands valid at this scope
3) about - provide info about this scope
4) register
5) login
6) logout
7) reset password (resetpass,forgot)
8) $featurename:
run commands from a feature without needing to first use the feature command:
Instead of "feature gpress", "look", "read 1":
"gp look", "gp read 1"
Using this sets the scope for future commands.
9) terms - terms of service
10) privacy - privacy policy

gPress specific commands:
1) look (sort) (topic) (page)
2) topics/cats (show all topics)
3) page (#) - turn to this page (keeping your current selections)
4) next (page)
5) final (page)
6) prev/last (page)
7) first (page)
8) read (post number, or post name)
gp read "Terminal Dreams, Part I"
gp read 16
gp search "Terminal Dreams"

look (sort) (topic) (page)

look old/new (created)
look a (alphabetical order, A-Z)
look z (alph order, Z-A)
look hot/cold (updated)

*/
$commands = [
"help" => "help",
"about" => "about",
"register" => "register",
"login" => "login",
"logout" => "logout",
"resetpass" => "forgot",
"forgot" => "forgot",
"gp" => "gpress",
"gpress" => "gpress",
"th" => "thehunt",
"thehunt" => "thehunt",
"sm" => "sm",
"ult" => "ult",
"fitb" => "fitb",
"plusminus" => "plusminus",
"+-" => "plusminus",
"maths" => "maths",
];

$cmd_array = explode(" ", $command, 2);
$debug['cmds'] = $cmd_array;
if(!in_array($cmd_array[0], $commands))
{
	$errors['invalid_cmd'] = true;
}
else 
{
if(!empty($cmd_array[1]))
{
	$string = $cmd_array[1];
}
 else 
 {
 	$string = "";
 }
 $cmd_string = "cli_{$cmd_array[0]}";
$cmd_string();	
}
return 1;
}

function cli_help()
{
	echo "This is the help function.";
}

function cli_about()
{
	$_GET['a'] = "about";
}

function cli_credits()
{
	$_GET['a'] = "credits";
	$_GET['z'] = 1;
	
}

function cli_terms()
{
	$_GET['a'] = "terms";
	$_GET['z'] = 1;
}

function cli_privacy()
{
	$_GET['a'] = "privacy";
	$_GET['z'] = 1;
}

/*
    Functions for Mail:
        Add new message
*/
function newMailThread($sender, $thread_name)
{
    global $pdo;
    $query = $pdo->prepare("INSERT INTO `msgr_threads` 
    (`thread_starter`, `thread_name`)
    VALUES (:thread_starter, :thread_name)");
    $query->execute([
    ":thread_starter" => $sender,
    ":thread_name" => $thread_name,
    ]);
    $thread_id = $pdo->lastInsertId();
    return $thread_id;

}

function newMailMessage($sender, $thread_id, $body, $msg_id)
{
    global $pdo;
    $query = $pdo->prepare("INSERT INTO `msgr_msgs` 
    (`local_msg_id`, `sender_id`, `thread_id`, `msg_body`)
    VALUES (:local_msg_id, :sender_id, :thread_id, :msg_body)");
    $query->execute([
    ":local_msg_id" => $msg_id,
    ":sender_id" => $sender,
    ":thread_id" => $thread_id,
    ":msg_body" => $body,
    ]);
}

function editMailMessage($msg_id, $msg_body)
{
    global $pdo;
    $query = $pdo->prepare("UPDATE `msgr_msgs` SET
    `msg_body` = :msg_body WHERE local_msg_id = :msg_id");
    $query->execute([
    ":msg_body" => $msg_body,
    ":msg_id" => $msg_id
    ]);
}

function newChapter($thread_id, $chapter_num, $first_post, $last_post, $chapter_name)
{
    global $pdo;
    $query = $pdo->prepare("INSERT INTO `msgr_chapters` 
    (`thread_id`, `chapter_num`, `first_post`, `last_post`, `chapter_name`)
    VALUES (:thread_id, :chapter_num, :first_post, :last_post, :chapter_name)");
    $query->execute([
    ":thread_id" => $thread_id,
    ":chapter_num" => $chapter_num,
    ":first_post" => $first_post,
    ":last_post" => $last_post,
    ":chapter_name" => $chapter_name,
    ]);
}

function newThreadAuths($thread_id, $member_id, $auth_level)
{
    global $pdo;
    $query = $pdo->prepare("REPLACE INTO `msgr_thread_auths` 
    (`thread_id`, `member_id`, `auth_level`)
    VALUES (:thread_id, :sender_id, :auth_level)");
    var_dump($query);
    $query->execute([
    ":thread_id" => $thread_id,
    ":sender_id" => $member_id,
    ":auth_level" => $auth_level,
    ]);

}