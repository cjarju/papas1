<?php

    require_once '../../assets/php/php_functions.php';

    if (!isset($_SESSION['username'])) {
        redirect ('../../');
    }

    /* this session has worn out its welcome; kill it and start a brand new one */

    session_unset();     /* unset $_SESSION variable for the run-time */
    session_destroy();   /* destroy session data in storage */

    session_start(); /* start a brand new one */
    
	/*
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = array();
    }
    array_push($_SESSION['flash'], "Salida con éxito.");
    */
	
    redirect("../../");


