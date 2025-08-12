<?php
session_start();
session_destroy();
header('Location: http://localhost:8888/BakerySimulator/public/login.php');
exit;
