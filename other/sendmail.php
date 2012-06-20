<HTML>
<body>
MAIL SETTING for Iseries<br>
Define a real name of your mail Server<br>
<center>
SMTP = mail.gmail.com </center>
<br>
Define a real name of your mail Server<br>
<center>
smtp_port = 25</center>
<br>
Define an any address you want<br>
<center>
sendmail_from = shlomo@zend.com</center><br>
</body>

<?
// Using the ini_set()
ini_set("SMTP","smtp.gmail.com");
ini_set("smtp_port","25");
ini_set("sendmail_from","Name <huunam0@gmail.com>");
ini_set("username","huunam0@gmail.com");
ini_set("password","password");
// The message
$message = "The mail message was sent with the following mail setting:\r\nSMTP = mail.zend.com\r\nsmtp_port = 25\r\nsendmail_from = YourMail@address.com";

// Send
$headers = "From: shlomo@zend.com";

mail('huunam0@gmail.com', 'My Subject', $message, $headers);

echo "Check your email now....<BR>";
?>
</HTML>