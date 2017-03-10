<?php
require_once 'lib/index.php';
session_unset();
session_destroy();
header('Location: ' . $serverURL);
