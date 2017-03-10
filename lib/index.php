<?php
require_once './vendor/autoload.php';
require_once 'lib.config.php';
require_once 'class.models.php';
$serverURL = 'http://' . $_SERVER['HTTP_HOST'] . '/'. __DIR__;
session_start();