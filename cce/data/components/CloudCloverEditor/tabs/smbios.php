<?php
/**
 * Cloud Clover Editor
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

function smbios() {
    global $text, $config;

    $smbiosList = simplexml_load_file('data/res/smbios-list.xml');
    $CHval = $config->getVals('SMBIOS/Memory/Channels');
    $boardTypeVal = $config->getVals('SMBIOS/BoardType');
    $channels = $boardType = $chassType = $smbMOpt = $manufLoc = $curMacSpecs = array();
    $chAssetT = 'MacBook-White,MacBook-Black,MacBook-Aluminum,Air-Enclosure,Mini-Aluminum,iMac,iMac-Aluminum,Pro-Enclosure,Xserve';
    $chI = 1;

    // Ozmosis
    $isOz = isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) && $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'oz' ? true:false;
    $hiddenEl = $isOz ? 'hidden-el':'';
    $disable = $isOz ? 'disabled':'';
    $colorDisabled = $isOz ? 'class="color-disabled"':'';
    $minLengthReq = $isOz ? 'require-minlen':'';

    if ($isOz) {
        $dataPath = 'Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102';
        $biosDatePath = 'BiosDate';
        $familyPath = 'ProductFamily';
        $biosVendorPath = 'FirmwareVendor';
        $boardIdPath = 'ProductId';
        $boardSerialPath = 'BaseBoardSerial';
        $serialNumbPath = 'SystemSerial';
        $sysVersionPath = 'SystemVersion';
        $chassisTypePath = 'EnclosureType';
        $selectedMac = $config->getVals($dataPath.'/ProductName');
        $chassTypeVal = sprintf("%02X", $config->getVals($dataPath.'/EnclosureType'));
        $productName = $selectedMac;
        $family = $dataPath.'/ProductFamily';
        $biosDate = $dataPath.'/BiosDate';
        $biosVersion = $dataPath.'/BiosVersion';
        $biosVendor = $dataPath.'/FirmwareVendor';
        $boardId = $dataPath.'/ProductId';
        $boardVersion = $dataPath.'/BoardVersion';
        $boardSerial = $dataPath.'/BaseBoardSerial';
        $chasAssTag = $dataPath.'/ChassisAssetTag';
        $fwFeatures = $dataPath.'/FirmwareFeatures';
        $fwFeaturesMask = $dataPath.'/FirmwareFeaturesMask';
        $serialNumb = $dataPath.'/SystemSerial';
        $manufacturer = $dataPath.'/Manufacturer';
        $sysVersion = $dataPath.'/SystemVersion';

    } else {
        $dataPath = 'SMBIOS';
        $biosDatePath = 'BiosReleaseDate';
        $familyPath = 'Family';
        $biosVendorPath = 'BiosVendor';
        $boardIdPath = 'Board-ID';
        $boardSerialPath = 'BoardSerialNumber';
        $serialNumbPath = 'SerialNumber';
        $sysVersionPath = 'Version';
        $chassisTypePath = 'ChassisType';
        $selectedMac = $config->getVals('SMBIOS/ProductName');
        $chassTypeVal = substr($config->getVals('SMBIOS/ChassisType'), 2);
        $productName = $selectedMac;
        $family = 'SMBIOS/Family';
        $biosDate = 'SMBIOS/BiosReleaseDate';
        $biosVersion = 'SMBIOS/BiosVersion';
        $biosVendor = 'SMBIOS/BiosVendor';
        $boardId = 'SMBIOS/Board-ID';
        $boardVersion = 'SMBIOS/BoardVersion';
        $boardSerial = 'SMBIOS/BoardSerialNumber';
        $chasAssTag = 'SMBIOS/ChassisAssetTag';
        $fwFeatures = 'SMBIOS/FirmwareFeatures';
        $fwFeaturesMask = 'SMBIOS/FirmwareFeaturesMask';
        $serialNumb = 'SMBIOS/SerialNumber';
        $manufacturer = 'SMBIOS/Manufacturer';
        $sysVersion = 'SMBIOS/Version';
    }

    foreach ($smbiosList as $mac) {
        if ($mac->ProductName == $selectedMac) {
            $curMacSpecs = $mac;
            break;
        }
    }

    $yearMac = isset($curMacSpecs->Year) ? explode(',',$curMacSpecs->Year):array();
    $cpuSpec = isset($curMacSpecs->specs->cpu) ? $curMacSpecs->specs->cpu:$text['no_specs'];
    $ramSpec = isset($curMacSpecs->specs->ram) ? $curMacSpecs->specs->ram:$text['no_specs'];
    $gfxSpec = isset($curMacSpecs->specs->gfx) ? $curMacSpecs->specs->gfx:$text['no_specs'];

    foreach ($text as $k => $v) {
        if (substr($k,0,3) === 'ch_') $channels[] = $k;
        if (substr($k,0,4) === 'bdt_') $boardType[] = $k;
        if (substr($k,0,4) === 'cht_') $chassType[] = $k;
        if (substr($k,0,4) === 'sch_') $smbMOpt[] = $k;
        if (substr($k,0,4) === 'mnl_') $manufLoc[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['smbios']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $text['sel_model']; ?></label>
                <select class="cce-sel smbios-sel">
                    <option value=""></option>
                    <?php foreach ($smbiosList as $mac) {
                        if (isset($mac->PrettyName)) { ?>
                        <option value="<?php echo $mac->ProductName; ?>" <?php echo isSelected($mac->ProductName, $selectedMac); ?>><?php echo $mac->PrettyName; ?></option>
                    <?php } } ?>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="well smbios-specs">
                <div class="row">
                    <div class=" col-xs-12 col-md-3"><?php echo $text['cpu']; ?>:</div>
                    <div class="col-xs-12 col-md-9 text-right smbios-speccpu"><?php echo $cpuSpec; ?></div>
                </div>

                <div class="row padtop-10">
                    <div class=" col-xs-12 col-md-3"><?php echo $text['ram']; ?>:</div>
                    <div class="col-xs-12 col-md-9 text-right smbios-specram"><?php echo $ramSpec; ?></div>
                </div>

                <div class="row padtop-10">
                    <div class=" col-xs-12 col-md-3"><?php echo $text['gfx_card']; ?>:</div>
                    <div class="col-xs-12 col-md-9 text-right smbios-specgfx"><?php echo $gfxSpec; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['pd_name']; ?></label>
                <input type="text" class="cce-text form-control smbios-pdname to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="ProductName" value="<?php echo $productName; ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['pd_family']; ?></label>
                <input type="text" class="cce-text form-control smbios-family to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $familyPath; ?>" value="<?php echo $config->getVals($family); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['bios_rel_date']; ?></label>
                <input type="text" class="cce-text form-control smbios-breldate to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $biosDatePath; ?>" value="<?php echo $config->getVals($biosDate); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="form-group">
                <label><?php echo $text['bios_ver']; ?></label>
                <input type="text" class="cce-text form-control smbios-bver to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="BiosVersion" value="<?php echo $config->getVals($biosVersion); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['bios_vendor']; ?></label>
                <input type="text" class="cce-text form-control smbios-bvend to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $biosVendorPath; ?>" value="<?php echo $config->getVals($biosVendor); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['board_id']; ?></label>
                <input type="text" class="cce-text form-control smbios-boardid to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $boardIdPath; ?>" value="<?php echo $config->getVals($boardId); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="form-group">
                <label><?php echo $text['board_ver']; ?></label>
                <input type="text" class="cce-text form-control smbios-boardver to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="BoardVersion" value="<?php echo $config->getVals($boardVersion); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-5 col-md-3 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['board_type']; ?></label>
                <select class="cce-sel smbios-boardtp" <?php echo $disable; ?> data-path="SMBIOS" data-field="BoardType">
                    <option value=""></option>
                    <?php foreach ($boardType as $bdt) { ?>
                        <option value="<?php echo substr($bdt,4); ?>" <?php echo isSelected(substr($bdt,4), $boardTypeVal); ?>><?php echo $text[$bdt]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-7 col-md-4">
            <div class="form-group">
                <label><?php echo $text['board_sn']; ?> <span class="minlen-er hidden-el"> (Min Length: 17)</span></label>
                <input type="text" class="cce-text form-control smbios-boardsn to-oz-path <?php echo $minLengthReq; ?>" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $boardSerialPath; ?>" value="<?php echo $config->getVals($boardSerial); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['chass_asset']; ?></label>
                <input type="text" class="cce-combo smbios-chasstype to-oz-path" value="<?php echo $config->getVals($chasAssTag); ?>"
                       data-path="<?php echo $dataPath; ?>" data-field="ChassisAssetTag" data-combo="<?php echo $chAssetT; ?>" />
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['loc_in_chass']; ?></label>
                <input type="text" class="cce-text form-control smbios-locinchass" <?php echo $disable; ?> data-path="SMBIOS" data-field="LocationInChassis" value="<?php echo $config->getVals('SMBIOS/LocationInChassis'); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['chass_type']; ?></label>
                <select class="cce-sel smbios-chtp to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $chassisTypePath; ?>">
                    <option value=""></option>
                    <?php foreach ($chassType as $cht) {
                        $subCh = substr($cht,4); ?>
                        <option value="<?php echo $subCh; ?>" <?php echo isSelected($subCh, $chassTypeVal); ?>><?php echo $text[$cht]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['chass_manufacturer']; ?></label>
                <input type="text" class="cce-text form-control smbios-chman" <?php echo $disable; ?> data-path="SMBIOS" data-field="ChassisManufacturer" value="<?php echo $config->getVals('SMBIOS/ChassisManufacturer'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['plat_feature']; ?></label>
                <input type="text" class="cce-text form-control smbios-platfeatures" <?php echo $disable; ?> data-path="SMBIOS" data-field="PlatformFeature" placeholder="Hex value" value="<?php echo $config->getVals('SMBIOS/PlatformFeature'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['serial_numb']; ?></label>
                <div class="input-group">
                    <input type="text" class="cce-text form-control smbios-sn to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $serialNumbPath; ?>" value="<?php echo $config->getVals($serialNumb); ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target=".serialN-modal"><i class="fa icon-gear"></i> </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-12 col-md-6 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['sm_uuid']; ?></label>
                <input type="text" class="cce-text form-control smbios-smuuid" <?php echo $disable; ?> data-path="SMBIOS" data-field="SmUUID" value="<?php echo $config->getVals('SMBIOS/SmUUID'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['fw_features']; ?></label>
                <input type="text" class="cce-text form-control to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="FirmwareFeatures" placeholder="Hex value" value="<?php echo $config->getVals($fwFeatures); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['fw_feature_mask']; ?></label>
                <input type="text" data-path="<?php echo $dataPath; ?>" data-field="FirmwareFeaturesMask" class="form-control cce-text" placeholder="Hex value" value="<?php echo $config->getVals($fwFeaturesMask); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-4 col-md-2 oz-disabled">
            <div class="form-group">
                <label <?php echo $colorDisabled; ?>><?php echo $text['board_manufacturer']; ?></label>
                <input type="text" class="cce-text form-control smbios-boardman" <?php echo $disable; ?> data-path="SMBIOS" data-field="BoardManufacturer" value="<?php $config->getVals('SMBIOS/BoardManufacturer'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['manufacturer']; ?></label>
                <input type="text" class="cce-text form-control smbios-man to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="Manufacturer" value="<?php echo $config->getVals($manufacturer); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['version']; ?></label>
                <input type="text" class="cce-text form-control smbios-ver to-oz-path" data-path="<?php echo $dataPath; ?>" data-field="<?php echo $sysVersionPath; ?>" value="<?php echo $config->getVals($sysVersion); ?>" />
            </div>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-md-12 subtitle">
            <?php echo $text['misc']; ?>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-xs-12 col-md-12 cce-check-inline">
            <?php foreach ($smbMOpt as $smbM) {
                $checked = isChecked($config->getVals('SMBIOS/'.substr($smbM,4))); ?>
                <div class="checkbox" data-path="SMBIOS">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($smbM,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$smbM]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-md-12 title">
            <?php echo $text['ram_modules']; ?>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-md-12 subtitle">
            <?php echo $text['ram_inj']; ?>
        </div>
    </div>
    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-xs-12 col-md-12">
            <?php echo drawPatchTable('SMram',
                array($text['slot'], $text['sizeM'], $text['freqM'], $text['type'],
                    $text['vendor'], $text['serial'], $text['part']),
                $config->getVals('SMBIOS/Memory/Modules')); ?>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['channels']; ?></label>
                <select class="cce-sel" data-path="SMBIOS/Memory" data-field="Channels">
                    <option value=""></option>
                    <?php foreach ($channels as $ch) { ?>
                        <option value="<?php echo $chI; ?>" <?php echo isSelected($chI, $CHval); ?>><?php echo $text[$ch]; ?></option>
                        <?php ++$chI; } ?>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3 col-sm-offset-5 col-md-2 col-md-offset-0">
            <div class="form-group">
                <label><?php echo $text['slot_count']; ?></label>
                <input type="number" class="cce-numb form-control" data-path="SMBIOS/Memory" data-field="SlotCount" value="<?php echo $config->getVals('SMBIOS/Memory/SlotCount'); ?>" />
            </div>
        </div>
        <!-- col-md-8 here -->
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-md-12 title">
            <?php echo $text['slots']; ?>
        </div>
    </div>

    <div class="row nooz <?php echo $hiddenEl; ?>">
        <div class="col-md-12">
            <?php echo drawPatchTable('SMslots',
                array($text['device'], $text['id'], $text['name'], $text['type']),
                $config->getVals('SMBIOS/Slots')); ?>
        </div>
    </div>

    <!-- SMBIOS MODAL -->
    <div class="modal fade serialN-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $text['serial_gen']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2 class="smbios-final-serial"><?php echo $config->getVals('SMBIOS/SerialNumber'); ?></h2>
                        </div>
                    </div>
                    <div class="row padtop-10">
                        <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                                <label><?php echo $text['manufacturer_loc']; ?></label>
                                <select class="cce-sel smbios-manlcsel" <?php echo end($yearMac) >= 2012 ? 'disabled':''; ?>>
                                    <?php foreach ($manufLoc as $loc) { ?>
                                    <option value="<?php echo substr($loc,4); ?>"><?php echo $text[$loc]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-sm-offset-3 col-md-4 col-md-offset-3">
                            <div class="form-group">
                                <label><?php echo $text['manufacturer_yr']; ?></label>
                                <select class="cce-sel smbios-yrsnsel">
                                    <?php foreach ($yearMac as $yr) { ?>
                                    <option value="<?php echo $yr; ?>"><?php echo $yr; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row padtop-10">
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['manufacturer_wk']; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control smbios-weekN" value="" disabled />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default week-shake" type="button"><?php echo $text['shake']; ?></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['model_code']; ?></label>
                                <h4 class="smbios-mdl-code"><?php echo isset($curMacSpecs->ModelId) ? $curMacSpecs->ModelId:''; ?></h4>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['u_number']; ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control smbios-unitN" value="" disabled />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default unit-shake" type="button"><?php echo $text['shake']; ?></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }
