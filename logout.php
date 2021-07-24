<?php

require_once 'app/helpers.php';
session_start();
session_destroy();
header('location: signin.php');
