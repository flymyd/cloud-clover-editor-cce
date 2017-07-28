<?php
/**
 * cloverPlist: PHP Class to manipulate a Clover EFI Bootloader or Ozmosis configuration
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

require_once __DIR__.'/../CFPropertyListCCE/CFPropertyList.php';

class cloverPlist {
    /**
     * Currently loaded config array.
     *
     * @var array $plist
     */
    private $plist = array();

    /**
     * Outdated keys ready to be upgraded.
     *
     * @var array $upgradableKeys
     */
    private $upgradableKeys = array();

    /**
     * Ozmosis flag.
     *
     * @var bool $ozmosis
     */
    private $ozmosis = false;

    /**
     * cloverPlist constructor.
     *
     * @param string $plist
     */
    public function __construct($plist) {
        $this->setPlist($plist);
    }

    /**
     * Set $plist.
     *
     * @param string $plist
     */
    public function setPlist($plist) {
        if ($plist === 'new') $plist = $this->genEmptyConfig();

        $cfObj = new \CFPropertyList\CFPropertyList();
        $cfObj->parse($plist);
        $this->plist = $cfObj->toArray();
        unset($cfObj);

        if (isset($this->plist['Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102']) ||
            isset($this->plist['Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101']) ||
            isset($this->plist['Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82']) ) {
            $this->ozmosis = true;

            $cfObj = new \CFPropertyList\CFPropertyList(null, 0, $this->ozmosis);
            $cfObj->parse($plist);
            $this->plist = $cfObj->toArray();
            unset($cfObj);
        }
    }

    /**
     * Return $plist.
     *
     * @return array|mixed
     */
    public function getPlist() {
        return $this->plist;
    }

    /**
     * Return $plist as file.
     *
     * @return string
     */
    public function getPlistFile() {
        return $this->save($this->plist);
    }

    /**
     * Set the Ozmosis flag.
     */
    public function setOzmosis($bool=true) {
        $this->ozmosis = $bool;
    }

    /**
     * Get the Ozmosis flag.
     *
     * @return bool
     */
    public function isOzmosis() {
        return $this->ozmosis;
    }

    /**
     * Return the current value/s of an option.
     *
     * @param string $path
     * @return mixed
     */
    public function getVals($path) {
        return $this->cd($path);
    }

    /**
     * Set a value.
     *
     * @param string $path
     * @param array $val
     *
     * @note You cannot set multiple values.
     */
    public function setVal($path, $val) {
        $plist =& $this->cd($path);
        $valKey = array_keys($val);

        if ($plist == null)
            $plist =& $this->mkPath($path);

        if (array_key_exists($valKey[0], $plist)) {
            $value = array_values($val);
            $plist[$valKey[0]] = $value[0];
        } else {
            $plist += $val;
        }

        if (!is_numeric($valKey))
            ksort($plist);
    }

    /**
     * Unset a value and, if empty, the whole option.
     *
     * @param string $path
     * @param string $key
     */
    public function unsetVal($path, $key) {
        $plist =& $this->cd($path);
        $pathAr = explode('/', $path);
        $pathLen = count($pathAr)-1;

        if ($plist == null)
            return;

        while (true) {
            unset($plist[$key]);

            if (is_numeric($key))
                $plist = array_values($plist);

            if (count($this->getVals($path)) || $pathLen == -1)
                break;

            $key = $pathAr[$pathLen];
            unset($pathAr[$pathLen]);
            $path = implode('/', $pathAr);
            $plist =& $this->cd($path);

            --$pathLen;
        }
    }

    /**
     * Sort values.
     *
     * @param string $path
     * @param int|string $oldKey
     * @param int|string $newKey
     *
     * @note Does not support associative arrays
     */
    public function sortVals($path, $oldKey, $newKey) {
        $plist =& $this->cd($path);

        if (isset($plist[$oldKey]) && isset($plist[$newKey])) {
            $oldKeyVal = $plist[$oldKey];

            unset($plist[$oldKey]);
            array_splice($plist, $newKey, 0, array($oldKeyVal));

            $plist = array_values($plist);
        }
    }

    /**
     * Fix variable type.
     *
     * @param string $val
     *
     * @return mixed
     *
     * @note Ajax calls convert everything to string.
     * @note CFPropertyList requires the correct variable type to create a valid config file.
     */
    public function sanitizeVal($val) {

        switch ($val) {
            case 'true':
                $sanitized = true;
                break;
            case 'false':
                $sanitized = false;
                break;
            default:
                $sanitized = trim($val);
                break;
        }

        return $sanitized;
    }

    /**
     * Create a download dialog.
     *
     * @param string $fileName
     */
    public function export($fileName='cce-config') {
        $file = $this->save($this->plist);

        header('Content-Description: File Transfer');
        header("Content-Type: text/xml");
        header('Content-Disposition: attachment; filename="'.$fileName.'.plist"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.strlen($file));

        echo $file;
        exit();
    }

    /**
     * Check for outdated keys.
     *
     * @return bool
     */
    public function checkUpgrade() {
        $this->upgradableKeys = array();

        // check EDID < 3737
        if ($this->getVals('Graphics/InjectEDID') != null || $this->getVals('Graphics/CustomEDID') != null)
            $this->upgradableKeys[] = 'EDID';

        // check default boot bg color < 3830
        if ($this->getVals('SystemParameters/DefaultBackgroundColor') != null)
            $this->upgradableKeys[] = 'sysDefBgColor';

        // check new Way Flag < 4006
        if ($this->getVals('ACPI/DSDT/Fixes/NewWay_80000000') != null || $this->getVals('ACPI/DSDT/Fixes/FixDarwin_0002') != null)
            $this->upgradableKeys[] = 'newWayFix';

        return empty($this->upgradableKeys) ? false:true;
    }

    /**
     * Upgrade outdated keys.
     */
    public function upgradePlist() {

        for ($i=0, $len=count($this->upgradableKeys); $i<$len; ++$i) {
            switch ($this->upgradableKeys[$i]) {
                case 'EDID':
                    $inject = $this->getVals('Graphics/InjectEDID');
                    $custom = $this->getVals('Graphics/CustomEDID');

                    $this->unsetVal('Graphics', 'InjectEDID');
                    $this->unsetVal('Graphics', 'CustomEDID');
                    $this->setVal('Graphics/EDID', array('Inject' => (bool) $inject));
                    $this->setVal('Graphics/EDID', array('Custom' => $custom));
                    break;
                case 'sysDefBgColor':
                    $sysDefBgColor = $this->getVals('SystemParameters/DefaultBackgroundColor');

                    $this->unsetVal('SystemParameters', 'DefaultBackgroundColor');
                    $this->setVal('BootGraphics', array('DefaultBackgroundColor' => $sysDefBgColor));
                    break;
                case 'newWayFix': // You may need to adjust other options manually
                    $this->unsetVal('ACPI/DSDT/Fixes', 'NewWay_80000000');

                    if ($this->getVals('ACPI/DSDT/Fixes/FixDarwin_0002')) {
                        $this->unsetVal('ACPI/DSDT/Fixes', 'FixDarwin_0002');
                        $this->setVal('ACPI/DSDT/Fixes', array('FIX_DARWIN_10000' => true));
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Create the new config file.
     *
     * @param array $newPlist
     * @return string
     * @throws \CFPropertyList\PListException
     *
     * @note To prevent any changes to $plist, we use a copy here ($newPlist).
     */
    private function save($newPlist) {
        $cfObj = new \CFPropertyList\CFPropertyList();
        $cfD = new \CFPropertyList\CFTypeDetector(array('hexAsInt' => $this->ozmosis));

        if ($this->ozmosis)
            $this->prepareOzPlist($newPlist);
        else
            $this->preparePlist($newPlist);

        $cfObj->add( $cfD->toCFType($newPlist) );
        $plistFile = $cfObj->toXML();

        unset($cfD);
        unset($cfObj);

        return $plistFile;
    }

    /**
     * Create all the missing levels of a given path.
     *
     * @param string $path
     *
     * @return array|mixed
     */
    private function &mkPath($path) {
        $plist =& $this->plist;
        $pathEl = explode('/', $path);

        if ($plist == null)
            $plist = array();

        for ($i=0, $len=count($pathEl); $i<$len; ++$i) {
            if (!array_key_exists($pathEl[$i], $plist)) {
                $plist += array($pathEl[$i] => array());
                ksort($plist);
            }

            $plist =& $plist[$pathEl[$i]];
        }

        return $plist;
    }

    /**
     * Navigate through the config array.
     *
     * @param string $path
     *
     * @return array|mixed
     */
    private function &cd($path) {
        $plist =& $this->plist;
        $err = null; // kill warnings
        $cPath = explode('/', $path);

        for ($i=0, $len=count($cPath); $i<$len && $path != null; ++$i) {
            if (!array_key_exists($cPath[$i], $plist))
                return $err;

            $plist =& $plist[$cPath[$i]];
        }

        return $plist;
    }

    /**
     * Convert the values back to their original type.
     *
     * @param array $plist
     *
     * @note This function works with a copy of $plist.
     * @note Some values have a wrong type due to ajax calls or
     * @note because they are converted to a more comfortable type.
     * @note So, convert them back.
     */
    private function preparePlist(&$plist) {
        foreach ($plist as $key => &$vl) {
            if (is_array($vl)) {
                $this->preparePlist($vl);
            } else {
                switch (strval($key)) {
                    case 'Find':
                    case 'Replace':
                    case 'Custom': // Custom EDID
                        if (preg_match('/^(0[xX])?[a-fA-F0-9]+$/', $vl)) {
                            $vl = substr($vl, 0, 2) === '0x' ?
                                'b64_'.base64_encode(pack('H*', substr($vl, 2))) : 'b64_'.base64_encode(pack('H*', $vl));
                        } else {
                            $vl = 'b64_'.$vl;
                        }
                        break;
                    case 'Value':
                        $hasHexPrefix = substr($vl, 0, 2) == '0x' ? true:false;
                        $guessedDecVal = !$hasHexPrefix && is_numeric($vl) && strlen($vl) < 5 ? true:false;

                        if ($guessedDecVal) {
                            $vl = intval($vl);
                        } else if (!$guessedDecVal && preg_match('/^(0[xX])?[a-fA-F0-9]+$/', $vl)) {
                            $vl = $hasHexPrefix ?
                                'b64_'.base64_encode(pack('H*', substr($vl, 2))) : 'b64_'.base64_encode(pack('H*', $vl));
                        } else if ($vl === '') {
                            $vl = 'b64_'; // If empty, set as data field
                        }
                        break;
                    case 'ATI':
                    case 'NVidia':
                    case 'IntelGFX':
                    case 'LAN':
                    case 'WIFI':
                    case 'SATA':
                    case 'XHCI':
                    case 'IMEI':
                        if (is_bool($vl))
                            break;
                    case 'ResetAddress':
                    case 'ResetValue':
                    case 'C3Latency':
                    case 'VendorID':
                    case 'ProductID':
                    case 'HWPValue':
                    case 'PlatformFeature':
                    case 'FirmwareFeatures':
                    case 'FirmwareFeaturesMask':
                    case 'ig-platform-id':
                    case 'snb-platform-id':
                    case 'FakeCPUID':
                        if (substr($vl, 0, 2) != '0x') {
                            $vl = '0x'.$vl;
                        }
                        break;
                    case 'MaxMultiplier':
                    case 'MinMultiplier':
                    case 'PLimitDict':
                    case 'PluginType':
                    case 'UnderVoltStep':
                    case 'Length':
                    case 'BusSpeedkHz':
                    case 'FrequencyMHz':
                    case 'QPI':
                    case 'SavingMode':
                    case 'DualLink':
                    case 'VRAM':
                    case 'VideoPorts':
                    case 'RefCLK':
                    case 'BootDisplay':
                    case 'Timeout':
                    case 'Type':
                        if (!is_numeric($vl))
                            break;
                    case 'BoardType':
                    case 'Channels':
                    case 'SlotCount':
                    case 'ID':
                    case 'Frequency':
                    case 'Size':
                    case 'Slot':
                    case 'DoubleClick':
                    case 'Speed':
                    case 'UIScale':
                    case 'EFILoginHiDPI':
                        $vl = intval($vl);
                        break;
                    case 'ChassisType':
                        $vl = '0x'.substr($vl, -2); // TODO: temp
                        break;
                    case 'Inject':
                        if (!is_bool($vl)) {
                            if ($vl !== 'No' && $vl !== 'Detect')
                                $vl = substr($vl,0,2) === '0x' ? $vl:intval($vl);
                        }
                        break;
                    case 'CustomLogo':
                        if (substr($vl,0,1) !== "\\" && !is_bool($vl) &&
                                substr($vl,0,1) !== 'A' && substr($vl,0,1) !== 'N' &&
                                substr($vl,0,1) !== 'T') {
                            $vl = 'b64_'.base64_encode(pack('H*',$vl));
                        }
                        break;
                    case 'MatchOS':
                    case 'MatchBuild':
                        $vl = str_replace(' ','',$vl);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * Convert the values back to their original type.
     *
     * @param array $plist
     *
     * @note This function works with a copy of $plist.
     * @note Some values have a wrong type due to ajax calls or
     * @note because they are converted to a more comfortable type.
     * @note So, convert them back.
     */
    private function prepareOzPlist(&$plist) {
        foreach ($plist as $key => &$vl) {
            if (is_array($vl)) {
                $this->prepareOzPlist($vl);
            } else {
                switch (strval($key)) {
                    case 'Timestamp':
                    case 'TimeOut':
                        $vl = intval($vl);
                        break;
                    case 'csr-active-config':
                        $vl = substr($vl, 0,2) == '0x' ? 'fint_'.substr($vl, 2):'fint_'.$vl;
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * Generate an empty config
     *
     * @return string
     */
    private function genEmptyConfig() {
        $config = '<?xml version="1.0" encoding="UTF-8"?>
            <!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
            <plist version="1.0"><dict></dict></plist>';

        return $config;
    }
}