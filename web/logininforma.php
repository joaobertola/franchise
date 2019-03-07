<?php
	if (strtolower($_POST[codigo])==='brasil') {
		header('location: http://189.16.26.132/inform');
	} else {
		header('location: http://www.informsystem.com.br/web/index.php?web=logininform');
	}
?>