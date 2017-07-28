<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

session_start();
require_once __DIR__.'/../components/CloudCloverEditor/cloverPlist.php';
require_once __DIR__.'/../SimpleDB.php';

if ($_POST['vals'][0] != '' && $_POST['vals'][1] != '') {
    $re='k';

    switch ( filter_input(INPUT_POST,'type') ) {
        case 'setval':
            if ($_POST['vals'][2] != '') {
                if (isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) &&
                    ( ($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'oz' && substr($_POST['vals'][0], 0,8) != 'Defaults') ||
                        ($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'cce' && substr($_POST['vals'][0], 0,8) == 'Defaults') ))
                    break;

                $clover = unserialize($_SESSION['clover']);
                $clover->setVal($_POST['vals'][0],array($_POST['vals'][1] => $clover->sanitizeVal($_POST['vals'][2])));
                $_SESSION['clover'] = serialize($clover);
            }
            break;
        case 'unset':
            $clover = unserialize($_SESSION['clover']);

            if (is_array($_POST['vals'][1])) {
                for ($i=0, $len=count($_POST['vals'][1]); $i<$len; ++$i) {
                    if ($i !== 0)
                        $_POST['vals'][1][$i] = intval($_POST['vals'][1][$i]) - $i;

                    $clover->unsetVal($_POST['vals'][0], $_POST['vals'][1][$i]);
                }
            } else {
                $clover->unsetVal($_POST['vals'][0], $_POST['vals'][1]);
            }

            $_SESSION['clover'] = serialize($clover);
            break;
        case 'sortval':
            if ($_POST['vals'][2] != '') {
                $clover = unserialize($_SESSION['clover']);
                $clover->sortVals($_POST['vals'][0], $_POST['vals'][1],$_POST['vals'][2]);
                $_SESSION['clover'] = serialize($clover);
            }
            break;
        case 'sncfg':
            $clover = new cloverPlist('new');

            $_SESSION['config-list']['cce-config-'.$_POST['vals'][0]] = serialize($clover);
            break;
        case 'dscfg':
            unset($_SESSION['config-list'][$_POST['vals'][0]]);
            unset($_SESSION['cce-sett'][$_POST['vals'][0]]);
            break;
        case 'getcprops':
            $clover = unserialize($_SESSION['clover']);
            $re = $clover->getVals($_POST['vals'][0]);
            break;
        case 'copy':
            $source = unserialize($_SESSION['clover']);
            $dest = unserialize($_SESSION['config-list'][$_POST['vals'][1][0]]);
            $destIdx = count($dest->getVals($_POST['vals'][0]));

            for ($i=1, $len=count($_POST['vals'][1]); $i<$len; ++$i) {
                $content = $source->getVals($_POST['vals'][0].'/'.$_POST['vals'][1][$i]);
                $dest->setVal($_POST['vals'][0], array($destIdx => $content));

                ++$destIdx;
            }

            $_SESSION['config-list'][$_POST['vals'][1][0]] = serialize($dest);
            break;
        case 'ccesett':
            switch ($_POST['vals'][0]) {
                case 'mode':
                    $clover = unserialize($_SESSION['config-list'][$_SESSION['cur_idx']]);

                    $clover->setPlist('new');
                    $clover->setOzmosis( $_POST['vals'][1] == 'oz' ? true:false );

                    $_SESSION['clover'] = serialize($clover);
                    $_SESSION['config-list'][$_SESSION['cur_idx']] = $_SESSION['clover'];
                    $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] = $_POST['vals'][1];
                    break;
                case 'hb64':
                    $_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64'] = $_POST['vals'][1];
                    break;
                default:
                    break;
            }
            break;
        case 'bsearch': // cce bank search
            $db = new simpleDB('sqlite', 0, 0, 0);
            $cmd2 = $_POST['vals'][0] == 'listall' ? null:'WHERE `name` LIKE "%'.$_POST['vals'][0].'%"';
            $re = $db->fetch('config_list', $cmd2, 'all', 'SELECT `id`, `name`, `locked` FROM');
            $db->kill();
            break;
        case 'chksvtobnk':
            $confName = substr($_POST['vals'][0], 0, -7);
            $db = new simpleDB('sqlite', 0, 0, 0);
            $re = $db->rows('config_list', 'WHERE `name` = "'.$confName.'"', 'SELECT COUNT(*) FROM');
            $db->kill();
            break;
        case 'getbnkmd':
            $confName = substr($_POST['vals'][0], 0, -7);
            $db = new simpleDB('sqlite', 0, 0, 0);
            $key = $db->fetch('config_list', 'WHERE `name` = "'.$confName.'"', 'fetch', 'SELECT `edit_key` FROM');
            $re = $key['edit_key'] == 'public' ? 'public':'private';
            $db->kill();
            break;
        case 'genbnkkey':
            $db = new simpleDB('sqlite', 0, 0, 0);

            while (true) {
                $editKey = substr(md5(time()),0,11);
                $isDuplicate = $db->rows('config_list', 'WHERE `edit_key` = "'.$editKey.'"', 'SELECT COUNT(*) FROM');

                if (!$isDuplicate)
                    break;
            }

            $re = $editKey;
            $db->kill();
            break;
        case 'chkbnkname':
            $db = new simpleDB('sqlite', 0, 0, 0);
            $re = $db->rows('config_list', 'WHERE `name` = "'.$_POST['vals'][0].'"', 'SELECT COUNT(*) FROM');
            $db->kill();
            break;
        case 'svtobnk':
            $db = new simpleDB('sqlite', 0, 0, 0);
            $isCurSessConfig = $_SESSION['cur_idx'] == $_POST['vals'][2] ? true:false;
            $clover = unserialize($isCurSessConfig ? $_SESSION['clover']:$_SESSION['config-list'][$_POST['vals'][2]] );
            $content = $clover->getPlistFile();

            $type = substr($_POST['vals'][0], 0, 2) == 'k_' ? 'up':'mk';
            $edKey = $type == 'up' ? substr($_POST['vals'][0], 2):$_POST['vals'][0];

            $cmd2 = $type == 'up' ?
                "SET content = :val0, last_access = date('now') WHERE edit_key = \"".$edKey."\""
                :
                '(name, content, edit_key, locked) VALUES (:val0, :val1, :val2, :val3)';

            $values = $type == 'up' ? array($content) : array($_POST['vals'][1], $content, $_POST['vals'][0], $edKey == 'public'?'n':'y');
            $cmd1 = $type == 'up' ? 'UPDATE':'INSERT INTO';

            $re = $db->write_av('config_list', $cmd2, $values, $cmd1);
            $db->kill();

            if ($type == 'mk') {
                if (!$re) {
                    header('HTTP/1.1 500 DB error');
                    exit();
                }

                unset($_SESSION['config-list'][$_POST['vals'][2]]);

                $idx = $_POST['vals'][1].'-'.substr(md5(time()),0,6);
                $re = $idx;

                $_SESSION['config-list'][$idx] = serialize($clover);

                if ($isCurSessConfig)
                    $_SESSION['cur_idx'] = $idx;
            }
            break;
        case 'getOzTemplData':
            $clover = unserialize($_SESSION['clover']);
            $re = $clover->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/'.$_POST['vals'][0]);
            break;
        default:
            break;
    }

    echo json_encode($re);
}