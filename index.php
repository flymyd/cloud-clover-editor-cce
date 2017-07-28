<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */
if (version_compare(phpversion(), '5.3.3', '<')) {
    die('This PHP version is not supported, please upgrade!');
} else {
    header('Location: cce/index.php');
    exit();
}