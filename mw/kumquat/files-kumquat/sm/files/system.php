<?php
/*
Things to add:
Show some times, and update them when things are updated?
Show a list of batches that your user ID is involved with (for registered users)
AJAX?

Sections:
2) Check if there is a batch
3) Authenticate user (if so)
4) Detect message
If there is no message, goto 5
4a) Create batch (if needed)
4b) Create new user (if needed) - reauthenticate
4c) Create new post, or update old
1) Main form
5) Read batch
 */

 $errors = array();
/* The bridge - read input and decide what goes where
Several things to check for:
1. Text or not? If not, then is it for a batch? If it is, then view. If not, it's invalid.
2. Batch or not? If yes, then it's for an existing batch and is a post. If not, then it's for a new batch.
3. Name/password? Not necessary, but may be helpful. The first use of name/password sets the tone going forward for that name.
4. Count? How many is needed for a round. This sets it for a new batch, and ignores it for an existing one.
Check for submission information, then go in order; reading always comes last
 */
// oh, and do stuff to make sure the name/pass aren't too long !!

// Section 2: Detect if there is a batch
$batch = false;
$username = "";
$password = "";
$user_id = "";
if(isset($_POST['batch']) && !empty($_POST['batch'])){
	$batch = $_POST['batch'];
	$query = $pdo->prepare("SELECT * FROM sm_batches WHERE batch = ?");
	$query->execute([$batch]);
	$result = $query->fetch();
	if(!empty($result)){
		// batch exists
		$batch = $result['batch'];
		$count = $result['count'];
		}
		else {$batch = false; $errors["batch"]['invalid_batch_choice'] = true;}
}
// end 2: batch checking section
if(empty($errors["batch"]) && isset($_POST['door']) && $_POST['door'] == $_SESSION['ses_code']){
// Section 3: user preparation, both for new user and login

// Collect post data here
	if(isset($_POST['username']) && !empty($_POST['username'])){ 
	$username = $_POST['username'];
	if(mb_strlen($username, "UTF-8") > 32){$username = ""; $errors["batch"]['user_length'] = true;}
}
	if(isset($_POST['password']) && !empty($_POST['password'])){ $password = $_POST['password'];}
	if(isset($_SESSION['id'])){ $user_id = $_SESSION['id'];}
	// thanks to Emil Vikström on SE
	$ip = $_SERVER['REMOTE_ADDR'];
	if(empty($_POST['message'])){$msg = false; } else { $msg = $_POST['message']; }
	if(mb_strlen($msg, "UTF-8") > 20000){$msg = false; $errors["batch"]['message_length'] = true;}

// Authenticate here?
	// do you have the right to post?
	if($batch !== false){
			$query = $pdo->prepare(
				"SELECT * FROM sm_users WHERE 
				batch = :batch AND
				(
				(username = :username AND username != '') 
				OR 
				(user_id = :user_id AND user_id != 0 AND '' = :username)
				OR (ip = :ip AND user_id = 0 AND username = :username AND username = '')
				)"
				);
			$query->execute(
				[
				':batch' => $batch,
				':username' => $username,
				':user_id' => $user_id,
				':ip' => $ip,
				]
			);
	
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)){ // there is a user with these credentials
			$auth = false;
			if($username == $result['username'] && !empty($result['password'])){
				$password_ver = password_verify($password, $result['password']);
				if($password_ver === true) {$auth = true; $local_id = $result['local_id']; } else { $errors["batch"]["failed_authentication"] = true;}
			}
			elseif($user_id == $result['user_id']){
			$auth = true; $local_id = $result['local_id']; if($username != $result['username']){$username = "";}
			}
		}
	}
	
// end section 3: user preparation/authentication

// Section 4: Detect message
if(((!isset($auth) || $auth === true)) && !empty($msg)){
	// Section 4a: Create new batch (if needed)
	if($batch === false){
		$count = intval($_POST['count'] ?? '0');		
		if($count < 2 || $count > 19){ $count = 2; }
		// Two components to a batch - batch number, count
		$batch = bin2hex(random_bytes(8));
		// insert something here to make sure the batch doesn't exist already
		$query = $pdo->prepare("INSERT INTO `sm_batches` (`batch`, `count`) VALUES (:batch, :count)");
		$query->execute(
			[
			':batch' => $batch,
			':count' => $count
			]
		);
		// End 4a, New Batch Section
	}
	// Section 4b: Create new user (if needed)
	if(!isset($auth)){
	$query = $pdo->prepare("SELECT * FROM sm_users WHERE batch = :batch");
	$query->execute(
		[
		':batch' => $batch
		]
	);
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	/* we make a new user if:
	1. There are no users (so there must be a need for a new one)
	OR
	2. There are fewer users than the count, and you're not one of them
	*/
	$used = array();
	if(is_array($result)){
		$val = count($result);
		if($val < $count){
			foreach($result as $key => $value){
			// check for both the username (guest mode) AND user id (registered mode)
				if(($value['username'] == $username && !empty($username)) || ($value['user_id'] == $user_id && !empty($_SESSION['id'])) || $value['ip'] == $ip){
				$used['account_present'] = true;
				break;
				}
			}
		} else 
		{ // $count is met, so prevent new signups
		$used['count_met'] = true;}
	}
	if (empty($used)){
		$local_id = $val + 1;
		// Nothing has changed, so we're nearly ready to create a new user
		$password_hash = $password;
		if(!empty($password_hash)){$password_hash = password_hash($password_hash, PASSWORD_DEFAULT); }
		$query = $pdo->prepare("INSERT INTO `sm_users` (`batch`, `username`, `password`, `user_id`, `ip`, `local_id`) VALUES (:batch, :username, :password, :user_id, :ip, :local_id)");
		$query->execute(
			[
			':batch' => $batch,
			':username' => $username,
			':password' => $password_hash,
			':user_id' => $user_id,
			':ip' => $ip,
			':local_id' => $local_id
			]
		);
		$auth = true; // if you're good enough to make a user, you're good enough to post as that user
		}
	}
		// end of section 4b, create new user if needed

	// begin section 4c, create new message
	if(!empty($auth)){
		// You should have the right to post. Do you have a message in this set already?
		$query = $pdo->prepare(
			"SELECT * FROM sm_msgs WHERE batch = :batch AND 
			(
			local_id = :local_id
			)
			AND hidden = 1");
		$query->execute(
			[
			':batch' => $batch,
			':local_id' => $local_id,
			]
		);
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$date = date("Y-m-d H:i:s");
		if(!empty($result)){
			// there is, so update
			$query = $pdo->prepare(
				"UPDATE `sm_msgs` SET `msg` = :msg, `updated` = :updated WHERE `batch` = :batch AND hidden = 1 
				AND	`local_id` = :local_id
				");
			$query->execute(
			[
				':msg' => $msg,
				':batch' => $batch,
				':local_id' => $local_id,
				':updated' => $date
			]
			);
		$query = $pdo->prepare("UPDATE `sm_batches` SET `modified` = :modified WHERE `batch` = :batch");
		$query->execute([':modified' => $date, ':batch' => $batch]);
		}
		else {
			// there isn't, so make a new post
			// we need a batch, message, and username/user_id; we should have them all
			$query = $pdo->prepare("INSERT INTO `sm_msgs` (`batch`, `msg`, `local_id`, `hidden`) VALUES (:batch, :msg, :local_id, :hidden)");
			$query->execute(
			[
				':batch' => $batch,
				':msg' => $msg,
				':local_id' => $local_id,
				':hidden' => 1
			]
			);
			$query = $pdo->prepare("UPDATE `sm_batches` SET `modified` = :modified WHERE `batch` = :batch");
			$query->execute([':modified' => $date, ':batch' => $batch]);	
			// one more thing - check to see if hidden posts = count
			$query = $pdo->prepare("SELECT count(*) as `count` FROM sm_msgs WHERE hidden = 1 and batch = ?");
			$query->execute([$batch]);
			$result = $query->fetch();
			if($result['count'] == $count){
				$query = $pdo->prepare("UPDATE `sm_msgs` SET hidden = 0 WHERE hidden = 1 AND batch = ?");
				$query->execute([$batch]);
			}
				// that should do it!
		} // end create (vs. update)
	} // end create post section, 4c
} // end detected message section, 4
}
?>
