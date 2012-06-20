<?
echo "<html><head></head><body>";
function sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="") {
	require_once "phpmailer_include.php";
	$mail = new PHPMailer();
	$mail->SetLanguage("en", "language/");
	$mail->IsSMTP();
	$mail->Host = 'smtp.google.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'huunam0';
	$mail->Password = '9tandau';
	$mail->CharSet = "utf-8";
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	$mail->AddReplyTo($fromemail, $fromname);
	if ($cc) { 
		$cc = explode(", ", $cc);
		foreach ($cc as $ccaddress) {
			$mail->AddCC($ccaddress);
		}
	}
	if ($bcc) {
		$bcc = explode(", ", $bcc);
		foreach ($bcc as $bccaddress) {
			$mail->AddBCC($bccaddress);
		}
	}
	if ($type == "plain") {
		$mail->IsHTML(false);
	} else {
		$mail->IsHTML(true);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if(!$mail->Send()) {
		$mail->ErrorInfo;
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		return false;
	} else {
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return true;
	}
}
if (isset($_POST['update'])) {
	$fname="lastnum.txt";
	$startnum=17000700;
	$err_txt = "Note:<br>";
	if (strlen($_POST['fullname'])<3) {$err_txt.="'Full Name' must be filled.<br>";}
	if (strlen($_POST['phone'])<3) {$err_txt.="'Tel' must be filled.<br>";}
	if (strlen($_POST['email'])<3) {$err_txt.="'Email' must be filled.<br>";}
	if (strlen($_POST['address'])<3) {$err_txt.="'Address' must be filled.<br>";}
	if (!strlen($_POST['worktype'])) {$err_txt.="'Type of Work' must be filled.<br>";}
	if (!strlen($_POST['schedule'])) {$err_txt.="'Schedule' must be filled.<br>";}
	if (!strlen($_POST['budget'])) {$err_txt.="'Budget' must be filled.<br>";}
	if (strlen($err_txt)) { //no error
		echo "OK";
		if (file_exists($fname)) {
			$fh = fopen($fname, "r");
			if ($fh) {
				if (feof($fh)) { 
					$last=$startnum;
				} else {
					$last = fgets($fh);
					$last++;
				}
				fclose($fh);
				echo $last;
			} else {
				$last=$startnum;
			}
		} else {
			$last=$startnum;
		}
		$fh = fopen($fname, "w");
		fwrite($fh, $last);
		fclose($fh);
		sendemail ("NAM","huunam0@gmail.com","Mine","huunam0@gmail.com", "Contact Us Form", "This is an email from our site");
		//($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="")
	} else { //error, re-fill
		echo $err_txt;
	}
	//$numb=17000700;
	//$fh = fopen($fname, 'w') or die("can't open file '$fname'. ");
	//fwrite($fh, $numb);
	//fclose($fh);
	
	//echo $err_txt;
	//echo $_POST['fullname'];
	//echo strlen($_POST['fullname']);
} else {
	echo "<form  method='post' action='form.php'>";
?>
<div align="center">
<table width="0" border="0" align="center" cellpadding="4">
  <tr align="center">
    <td><strong>Full Name:</strong><br>      <input type="text" size="26" name="fullname"></td>
  </tr>
  <tr align="center">
    <td><strong>Tel:</strong><br>      <input type="text" size="26" name="phone"></td>
  </tr>
  <tr align="center">
    <td><strong>Fax <i>(optinal)</i>:</strong><br>      <input type="text" size="26" name="fax"></td>
  </tr>
  <tr align="center">
    <td><strong>Email:</strong><br><input type="text" size="26" name="email"></td>
  </tr>
  <tr align="center">
    <td><strong>Address:</strong><br><textarea name="address" cols="40" rows="5" width="78"></textarea></td>
  </tr>
  <tr align="center">
    <td><strong>Type of Work:</strong><br><input type="text" size="26" name="worktype"></td>
  </tr>
  <tr align="center">
    <td><strong>Schedule:</strong><br>
      <select name="schedule"><option value='ready'>Ready to start</option> 
      <option  value='spring2011'>Starting Spring 2011</option>
      <option  value='notsure'>Not sure</option></select></td>
  </tr>
  <tr align="center">
    <td><strong>Budget:</strong><br>
      <select name="budget">
        <option value='0'>$0 - $5,000</option>
        <option value='1'>$5,000 - $10,000</option>
        <option value='2'>$10,000 - $20,000</option>
        <option value='3'>$20,000 - $50,000</option>
        <option value='4'>Over $50,000</option>
      </select></td>
  </tr>
  <tr align="center">
    <td><input type="submit" value="Register Now" name="update"></td>
  </tr>
  <tr align="center">
    <td>&nbsp;</td>
  </tr>
</table></div>
<?
	echo "</form>";
}
echo "</body></html>";
?>