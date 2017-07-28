<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

session_start();
require_once __DIR__.'/../components/CloudCloverEditor/cloverPlist.php';
require_once __DIR__.'/../SimpleDB.php';

$head = 'index';
$type = filter_input(INPUT_POST, 'ucmd');

switch ($type) {
    case 'ocfg':
    case 'nfscfg':
        if ($type == 'nfscfg' ||
            (isset($_FILES['config']) && (preg_match("/.plist/", $_FILES['config']['name']) && $_FILES['config']['size'] < 1024 * 1024))) {

            $head = 'editor';

            if ($type == 'nfscfg') {
                $db = new simpleDB('sqlite', 0, 0, 0);
                $conf_list = $db->fetch('config_list', 'WHERE `id` = '.filter_input(INPUT_POST, 'bid'));
                $db->kill();
            }

            if (filter_has_var(INPUT_POST, 'resetse')) {
                $_SESSION = array();
            } else {
                unset($_SESSION['config-list'][$_SESSION['cur_idx']]);
                unset($_SESSION['cce-sett'][$_SESSION['cur_idx']]);
            }

            $config_content = $type == 'nfscfg' ? $conf_list['content']:file_get_contents($_FILES['config']['tmp_name']);
            $idx = $type == 'nfscfg' ? $conf_list['name']:str_replace('.plist','',$_FILES['config']['name']);
            $clover = new cloverPlist($config_content);

            $_SESSION['cur_idx'] = $idx.'-'.substr(md5(time()),0,6);
            $_SESSION['clover'] = serialize($clover);
            $_SESSION['config-list'][$_SESSION['cur_idx']] = $_SESSION['clover'];
            $_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64'] = 'hex';
            $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] = $clover->isOzmosis() ? 'oz':'cce';
        }
        break;
    case 'ncfg':
        $head = 'editor';

        if (filter_has_var(INPUT_POST, 'resetse')) {
            $_SESSION = array();
        } else {
            unset($_SESSION['config-list'][$_SESSION['cur_idx']]);
            unset($_SESSION['cce-sett'][$_SESSION['cur_idx']]);
        }

        $idx = 'cce-config-'.substr(md5(time()),0,6);
        $_SESSION['cur_idx'] = $idx;

        $clover = new cloverPlist('new');

        $_SESSION['clover'] = serialize($clover);
        $_SESSION['config-list'][$_SESSION['cur_idx']] = $_SESSION['clover'];
        break;
    case 'switchcfg':
        $head = 'editor';

        $_SESSION['config-list'][$_SESSION['cur_idx']] = $_SESSION['clover'];
        $_SESSION['clover'] = $_SESSION['config-list'][filter_input(INPUT_POST, 'idx')];
        $_SESSION['cur_idx'] = filter_input(INPUT_POST, 'idx');
        break;
    case 'upgrade':
        $head = 'editor';
        $clover = unserialize($_SESSION['clover']);

        $clover->checkUpgrade();
        $clover->upgradePlist();

        $_SESSION['clover'] = serialize($clover);
        break;
    default:
        break;
}

header('Location: ../../'.$head.'.php');
exit();