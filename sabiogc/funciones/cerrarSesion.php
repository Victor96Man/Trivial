<?php
	session_start();
	unset($_SESSION);
	session_destroy();
	session_regenerate_id(true);
	header("Location: ../index.php");