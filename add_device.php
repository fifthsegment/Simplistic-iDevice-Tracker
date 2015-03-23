
<?php
	function encrypt($pure_string, $encryption_key) {
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
	    return $encrypted_string;
	}
if (isset($_POST["add"])){
	$username = ($_POST["username"]);
	$password = ($_POST["password"]);
	$devicename = $_POST["devicename"];
	define("ENCRYPTION_KEY", $_GET['verified']);
	

	$username = base64_encode(encrypt($username, ENCRYPTION_KEY));
	$password = base64_encode(encrypt($password, ENCRYPTION_KEY));
	$string = '<?php $devices[] = new icloudAccount(';
	$string.= '\''.$username.'\', \''.$password.'\', \''.$devicename.'\'); ?>'."\n";

	echo $string;

        $string = mb_convert_encoding ($string, 'ASCII');
	
	file_put_contents('devices.php', $string, FILE_APPEND | LOCK_EX);
	print "Device added succesfully. Please refresh the main page to see your changes.<br>To delete the added device delete the line from the file devices.php that corresponds to this device's name.";



}else{
?>
<form action="<?php  echo basename($_SERVER['PHP_SELF']); ?>?verified=<?php echo $_GET['verified']?>" method="POST">
Username : <input type="text" name="username"><br>
Password : <input type="text" name="password"><br>
Device Name : <input type="text" name="devicename">
<input type="hidden" name="add">
<input type="submit" value="submit">
</form>
<?php
}

