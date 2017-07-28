<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

session_start();
require_once __DIR__.'/../components/CloudCloverEditor/cloverPlist.php';

$config = $_SESSION['cur_idx'] === filter_input(INPUT_POST, 'idx') ? $_SESSION['clover']:$_SESSION['config-list'][filter_input(INPUT_POST, 'idx')];
$clover = unserialize($config);
$confName = filter_input(INPUT_POST, 'filename') != '' ? filter_input(INPUT_POST, 'filename'):'cce-config';

$clover->export($confName);