<?php
echo "<form method='post' action='fopen.php'>";
echo "Enter<input type='text' name='url' size='150'><input type='submit' name='doit' value='Go'/>";
echo "</form><hr>";
if (isset($_POST['doit'])) 
{
	if ($fp = fopen($_POST['url'], 'r')) {
		$content = '';
		// keep reading until there's nothing left
		while ($line = fgets($fp, 1024)) {
			$content .= $line;
		}

		echo $content;
	} else {
		echo "Cant open";
	}
}

?>
