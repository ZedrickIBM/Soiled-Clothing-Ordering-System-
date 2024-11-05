<?php
    session_start();
    require "acclog.php";
    session_destroy();
    header("Location: soiled.php");
    exit();
