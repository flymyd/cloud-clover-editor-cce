<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

$lang = substr(filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE'),0,2);
$req = file_exists(__DIR__.'/'.$lang.'.php') ? $lang.'.php':'en.php';
require_once $req;
