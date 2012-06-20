<?php
echo "<form method='post' action='remote.php'>";
echo "Enter<input type='text' name='url' size='150'><input type='submit' name='doit' value='Go'/>";
echo "</form><hr>";
if (isset($_POST['doit'])) 
{
	$content = file_get_contents($_POST['url']);
	echo $content;//$_POST['url'];
}

?>
