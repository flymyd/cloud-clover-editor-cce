<?php
/**
 * Cloud Clover Editor: Common Functions and variables
 * Copyright (C) kylon - 2016-2017
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'links.php';
require_once 'components/langs/select.php';

/**
 * Head tags
 */
function head() {
    global $text; ?>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="author" content="kylon" />

    <title><?php echo $text['cce']; ?></title>
<?php }

/**
 * Get the title type of a custom entry
 *
 * @param array $array
 * @return string
 */
function getEntryTitleType($array) {
    $val = '';

    if ( isset($array['Title']) || isset($array['FullTitle']) ) {
        $val = isset($array['Title']) ? 'Title':'FullTitle';
    }

    return $val;
}

/**
 * Translate a bool value to Yes/No
 *
 * @param mixed $val
 * @return string
 *
 * @note Mainly used with comboboxes.
 */
function boolToText($val) {
    global $text;

    if (is_bool($val))
        $val = $val == true ? $text['yes']:$text['no'];

    return $val;
}

/**
 * Return the value of a property, if set
 *
 * @param array $array
 * @param string|integer $key
 * @return string
 */
function getPropVal($array, $key) {
    $val = '';

    if (isset($array[$key])) {
        $val = $array[$key];

        if ($key == 'Find' || $key == 'Replace') {
            if (isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64']) &&
                $_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64'] == 'b64') {
                $val = base64_encode(pack('H*', $array[$key]));
            }
        }
    }

    return $val;
}

/**
 * Quickly check if a checkbox is checked
 *
 * @param mixed $toCheck
 * @param string|integer $key
 * @return bool
 */
function isChecked($toCheck, $key=null) {
    $val = '';

    if ($key != null) {
        if (isset($toCheck[$key]) && ($toCheck[$key] === true || $toCheck[$key] === 'true') )
            $val = 'checked';
    } else {
        if ($toCheck === true || $toCheck === 'true')
            $val = 'checked';
    }

    return $val;
}

/**
 * Quickly check if a select option is selected
 *
 * @param mixed $expected
 * @param mixed $userVal
 *
 * @return string
 */
function isSelected($expected, $userVal) {
    $selected = '';

    switch ( gettype($expected) ) {
        case 'integer':
            $userVal = intval($userVal);
            break;
        case 'boolean':
            if ($userVal === false || $userVal === "false" || $userVal === true || $userVal === "true")
                (bool)$userVal;

            break;
        default:
            $userVal = $expected === '' ? null:strval($userVal);

            if ($expected != '' && strcmp($userVal, $expected) === 0)
                $userVal = $expected = true;

            break;
    }

    if ($userVal === $expected)
        $selected = 'selected';

    return $selected;
}

/**
 * Get Flag Bits
 *
 * @param string $flag
 *
 * @return array $bits
 */
function getFlagBits($flag='') {
    $intFlag = intval($flag,16);
    $v = $intFlag;
    $r = 0;
    $bits = array();

    while ($v !== 0) {
        while ($v >>= 1)
            ++$r;

        $v = $intFlag ^ 1 << $r;
        $intFlag = $v;
        $bits[] = $r;
        $r = 0;
    }

    return $bits;
}

/**
 * Get ACPI Loader Mode Flag Bits
 *
 * @param string $flag
 *
 * @return array $bits
 */
function getAcpiLoaderFlagBits($flag='', $sum=0, $bitsArray=array()) {
    $intFlag = intval($flag,16);
    $bitMask = $sum & $intFlag;
    $bits = array();

    foreach ($bitsArray as $bit => $text) {
        if ($bitMask & intval(substr($bit, 5), 16))
            $bits[] = substr($bit, 5);
    }

    return $bits;
}

/**
 * Check if $bit is set
 *
 * @param int $bit
 * @param array $bitsArray
 *
 * @return bool $set
 *
 * @note Use getFlagBits() or getAcpiLoaderFlagBits() to create $bitsArray.
 */
function isBitSelected($bit, $bitsArray) {
    $set = false;

    for ($i=0, $len=count($bitsArray); $i<$len; ++$i) {
        if ($bit == $bitsArray[$i]) {
            $set = true;
            break;
        }
    }

    return $set;
}

/**
 * Draw a CCE tab.
 *
 * @param string $s
 */
function drawTab($s='') {
    global $text;

    switch ($s) {
        case 'acpi':
            acpi();
            break;
        case 'boot':
            boot();
            break;
        case 'cloudclovereditor':
            cce();
            break;
        case 'cpu':
            cpu();
            break;
        case 'devices':
            devices();
            break;
        case 'disabledrivers':
            disDrv();
            break;
        case 'gui':
            gui();
            break;
        case 'graphics':
            gfx();
            break;
        case 'kernelandkextpatches':
            kernelKextPatch();
            break;
        case 'rtvariables':
            rtVariables();
            break;
        case 'smbios':
            smbios();
            break;
        case 'systemparameters':
            sysparam();
            break;
        case 'bootgraphics':
            bootGfx();
            break;
        case 'ozmosis':
            ozmosis();
            break;
        default:
            echo $text['default_msg'];
            break;
    }
}