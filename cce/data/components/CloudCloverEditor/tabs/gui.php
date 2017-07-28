<?php
/**
 * Cloud Clover Editor Tabs structure
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

function gui() {
    global $text, $config;

    $scrResCOpt = '1024x600,1024x768,1152x864,1280x720,1280x800,1280x1024,1360x768,1366x768,1400x1050,1440x900,
                    1600x900,1600x1200,1680x1050,1920x1080,2048x1252,2048x1536,2560x1600,2560x2048';
    $consoleCOpt = '0,Min,Max';
    $langOpt = array('en:0','es:0','it:0','ru:0','fr:0','pt:0','br:0','de:0','nl:0','pl:0','ua:0','cz:0','hr:0','id:0','ko:0');

    $scanVal = $config->getVals('GUI/Scan');
    $entriesVal = is_bool($scanVal) ? false:$config->getVals('GUI/Scan/Entries');
    $linuxVal = is_bool($scanVal) ? false:$config->getVals('GUI/Scan/Linux');
    $toolVal = is_bool($scanVal) ? false:$config->getVals('GUI/Scan/Tool');
    $scanKernelVal = $config->getVals('GUI/Scan/Kernel');
    $scanLegacyVal = $config->getVals('GUI/Scan/Legacy');
    $mouseEnable = $config->getVals('GUI/Mouse/Enabled');
    $mirrorEnable = $config->getVals('GUI/Mouse/Mirror');
    $languageVal = $config->getVals('GUI/Language');
    $customIcn = $config->getVals('GUI/CustomIcons');
    $onlyTxt = $config->getVals('GUI/TextOnly');
    $scanOpDisabled = is_bool($scanVal) || $scanVal == null ? 'disabled':'';
    $scanOpLabDis = is_bool($scanVal) || $scanVal == null ? 'class="color-disabled"':'';
    $customEntries = $config->getVals('GUI/Custom/Entries');
    $customEntries = $customEntries != null ? $customEntries:array('plc');
    $action = $customEntries[0] === 'plc' ? 'workaround':'';
    $entryI = $action === 'workaround' ? '-':0;
    $scanLeg = $scanKern = $centrySelType = $centryVolT = $centryChkOpt = array();
    $cLogoO = '';

    foreach ($text as $k => $v) {
        if (substr($k,0,5) === 'lscn_') $scanLeg[] = $k;
        if (substr($k,0,5) === 'kscn_') $scanKern[] = $k;
        if (substr($k,0,5) === 'cesl_') $centrySelType[] = $k;
        if (substr($k,0,5) === 'ceop_') $centryChkOpt[] = $k;
        if (substr($k,0,5) === 'volt_') $centryVolT[] = $k;
        if (substr($k,0,4) === 'osl_') $cLogoO .= $v.',';
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['bl_gui_sett']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['scan_opt']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-5">
                    <div class="form-group">
                        <label><?php echo $text['scan']; ?></label>
                        <select class="cce-sel enbl-scan" data-path="GUI" data-field="Scan">
                            <option value="true" <?php echo $scanVal || $scanVal === null ? 'selected':''; ?>><?php echo $text['scan_auto']; ?></option>
                            <option value="custom" <?php echo !is_bool($scanVal) && $scanVal !== null ? 'selected':''; ?>><?php echo $text['custom']; ?></option>
                            <option value="false" <?php echo !$scanVal && $scanVal !== null ? 'selected':''; ?>><?php echo $text['disabled']; ?></option>
                        </select>
                    </div>
                </div>
                <!-- col-md-7 here -->
            </div>

            <div class="row">
                <div class="col-md-12 cce-check-inline">
                    <div class="checkbox" data-path="GUI/Scan">
                        <label <?php echo $scanOpLabDis; ?>>
                            <input type="checkbox" class="cce-checkbox scanOp" data-field="Entries" <?php echo $scanOpDisabled; echo isChecked($entriesVal); ?> /> <?php echo $text['scan_entries']; ?>
                        </label>
                    </div>
                    <div class="checkbox" data-path="GUI/Scan">
                        <label <?php echo $scanOpLabDis; ?>>
                            <input type="checkbox" class="cce-checkbox scanOp" data-field="Linux" <?php echo $scanOpDisabled; echo isChecked($linuxVal); ?> /> <?php echo $text['scan_linux']; ?>
                        </label>
                    </div>
                    <div class="checkbox" data-path="GUI/Scan">
                        <label <?php echo $scanOpLabDis; ?>>
                            <input type="checkbox" class="cce-checkbox scanOp" data-field="Tool" <?php echo $scanOpDisabled; echo isChecked($toolVal); ?> /> <?php echo $text['scan_tools']; ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label <?php echo $scanOpLabDis; ?>><?php echo $text['scan_kernel']; ?></label>
                        <select class="cce-sel scanOp" data-path="GUI/Scan" data-field="Kernel" <?php echo $scanOpDisabled; ?>>
                            <?php foreach ($scanKern as $kern) { ?>
                                <option value="<?php echo str_replace(' ','',substr($kern,5)); ?>" <?php echo isSelected(substr($kern,5), $scanKernelVal); ?>><?php echo $text[$kern]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label <?php echo $scanOpLabDis; ?>><?php echo $text['scan_legacy']; ?></label>
                        <select class="cce-sel scanOp" data-path="GUI/Scan" data-field="Legacy" <?php echo $scanOpDisabled; ?>>
                            <?php foreach ($scanLeg as $legc) { ?>
                                <option value="<?php echo str_replace(' ','',substr($legc,5)); ?>" <?php echo isSelected(substr($legc,5), $scanLegacyVal); ?>><?php echo $text[$legc]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['mouse']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 cce-check-inline">
                    <div class="checkbox" data-path="GUI/Mouse">
                        <label>
                            <input type="checkbox" class="cce-checkbox" data-field="Enabled" <?php echo isChecked($mouseEnable); ?> /> <?php echo $text['enabled']; ?>
                        </label>
                    </div>
                    <div class="checkbox" data-path="GUI/Mouse">
                        <label>
                            <input type="checkbox" class="cce-checkbox" data-field="Mirror" <?php echo isChecked($mirrorEnable); ?> /> <?php echo $text['mirror']; ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['dbl_clk']; ?></label>
                        <input type="number" min="0" class="cce-numb form-control" data-path="GUI/Mouse" data-field="DoubleClick" value="<?php echo $config->getVals('GUI/Mouse/DoubleClick'); ?>" />
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['speed']; ?></label>
                        <input type="number" min="0" class="cce-numb form-control" data-path="GUI/Mouse" data-field="Speed" value="<?php echo $config->getVals('GUI/Mouse/Speed'); ?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['misc']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="form-group">
                        <label><?php echo $text['language']; ?></label>
                        <select class="cce-sel" data-path="GUI" data-field="Language">
                            <option value=""></option>
                            <?php foreach ($langOpt as $lg) { ?>
                                <option value="<?php echo $lg; ?>" <?php echo isSelected($lg, $languageVal); ?>><?php echo substr($lg,0,2); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-7">
                    <div class="form-group">
                        <label><?php echo $text['screen_res']; ?></label>
                        <input type="text" class="cce-combo" value="<?php echo $config->getVals('GUI/ScreenResolution'); ?>"
                               data-path="GUI" data-field="ScreenResolution" data-combo="<?php echo $scrResCOpt; ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="form-group">
                        <label><?php echo $text['console_mode']; ?></label>
                        <input type="text" class="cce-combo" value="<?php echo $config->getVals('GUI/ConsoleMode'); ?>"
                               data-path="GUI" data-field="ConsoleMode" data-combo="<?php echo $consoleCOpt; ?>" />
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-7">
                    <div class="form-group">
                        <label><?php echo $text['theme']; ?></label>
                        <input type="text" class="cce-text form-control" data-path="GUI" data-field="Theme" value="<?php echo $config->getVals('GUI/Theme'); ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 cce-check-inline">
                    <div class="checkbox" data-path="GUI">
                        <label>
                            <input type="checkbox" class="cce-checkbox" data-field="TextOnly" <?php echo isChecked($onlyTxt); ?> /> <?php echo $text['text_only']; ?>
                        </label>
                    </div>
                    <div class="checkbox" data-path="GUI">
                        <label>
                            <input type="checkbox" class="cce-checkbox" data-field="CustomIcons" <?php echo isChecked($customIcn); ?> /> <?php echo $text['custom_icons']; ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['bl_entries_sett']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['custom_entr']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div data-id="add-customEntry" class="addTbBtn noDelBtn bg-green-flat single-item-add"><i class="fa icon-plus"></i> </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-body single-items-panel panel-centry cce-sortable" data-sid="panel-centry">
                            <?php  foreach ($customEntries as $entry) {
                                $getTitle = getEntryTitleType($entry);
                                $entryTitleVal = $getTitle !== '' ? $entry[$getTitle]:'';
                                $entryTitleDataField = $getTitle === '' ? 'Title':$getTitle;
                                $fullTitleChecked = $getTitle === 'FullTitle' ? 'checked':'';
                                $volumeOsType = getPropVal($entry, 'Type');
                                $volumeType = getPropVal($entry, 'VolumeType');
                                $hiddenVal = getPropVal($entry, 'Hidden');
                                $injectKextVal = getPropVal($entry, 'InjectKexts');

                                if ($action !== 'workaround') { ?>
                            <div class="single-item single-centry" data-toggle="modal" data-target=".modalentry-<?php echo $entryI; ?>">
                                <div class="centry-sort-handle"><i class="fa icon-hand-paper-o"></i></div>
                                <div><i class="fa icon-drive single-item-icon"></i></div>
                                <div class="single-item-title centry-list-title" data-subid="<?php echo $entryI; ?>">
                                    <?php echo strlen($entryTitleVal) > 12 ? substr($entryTitleVal,0,13).'..':$entryTitleVal; ?>
                                </div>
                                <div>
                                    <button data-id="cp-cEntry" class="single-item-cp-btn bg-yellow-flat left copycEntry-btn" data-path="GUI/Custom/Entries" data-index="<?php echo $entryI; ?>">
                                        <i class="fa icon-files-o"></i>
                                    </button>
                                    <button data-id="del-cEntry" class="single-item-del-btn left delpatch-btn" data-path="GUI/Custom/Entries" data-index="<?php echo $entryI; ?>">
                                        <i class="fa icon-times"></i>
                                    </button>
                                </div>
                            </div>
                                <?php } ?>

                            <div class="modal fade <?php echo 'modalentry-'.$entryI; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title modal-centry-title" data-subid="<?php echo $entryI; ?>"><?php echo $entryTitleVal; ?></h4>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $text['volume']; ?></label>
                                                        <input type="text" class="cce-text form-control" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                               data-field="Volume" value="<?php echo getPropVal($entry, 'Volume'); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $text['title']; ?></label>
                                                        <input type="text" class="cce-text form-control single-item-title-upd centry-title" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                               data-field="<?php echo $entryTitleDataField; ?>" data-subid="<?php echo $entryI; ?>" value="<?php echo $entryTitleVal; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-9 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $text['path']; ?></label>
                                                        <input type="text" class="cce-text form-control" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                               data-field="Path" value="<?php echo getPropVal($entry, 'Path'); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-md-2">
                                                    <div class="form-group">
                                                        <label><?php echo $text['hotkey']; ?></label>
                                                        <input type="text" class="cce-text form-control" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                               data-field="Hotkey" value="<?php echo getPropVal($entry, 'Hotkey'); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label><?php echo $text['type']; ?></label>
                                                        <select class="cce-sel" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="Type">
                                                            <option value=""></option>
                                                            <?php foreach ($centrySelType as $cTp) { ?>
                                                                <option value="<?php echo substr($cTp,5); ?>" <?php echo isSelected(substr($cTp,5), $volumeOsType); ?>><?php echo $text[$cTp]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label><?php echo $text['volume_type']; ?></label>
                                                        <select class="cce-sel" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="VolumeType">
                                                            <option value=""></option>
                                                            <?php foreach ($centryVolT as $volt) { ?>
                                                                <option value="<?php echo substr($volt,5); ?>" <?php echo isSelected(substr($volt,5), $volumeType); ?>><?php echo $text[$volt]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $text['arguments']; ?></label>
                                                        <textarea class="form-control cce-text" rows="3" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                            data-field="Arguments"><?php echo getPropVal($entry, 'Arguments'); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $text['add_arguments']; ?></label>
                                                        <textarea class="form-control cce-text" rows="3" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>"
                                                            data-field="AddArguments"><?php echo getPropVal($entry, 'AddArguments'); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 subtitle">
                                                    <?php echo $text['theme']; ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo $text['os_logo']; ?></label>
                                                        <input type="text" class="cce-combo" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="CustomLogo"
                                                               data-combo="<?php echo $cLogoO; ?>" placeholder="<?php echo $text['c_logo_placeholder']; ?>"
                                                               value="<?php echo boolToText($config->getVals('GUI/Custom/Entries/'.$entryI.'/CustomLogo')); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo $text['boot_bg_color']; ?></label>
                                                        <input type="text" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="BootBgColor" class="form-control cce-text"
                                                               value="<?php echo $config->getVals('GUI/Custom/Entries/'.$entryI.'/BootBgColor'); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo $text['image']; ?></label>
                                                        <input type="text" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="Image" class="form-control cce-text"
                                                               value="<?php echo $config->getVals('GUI/Custom/Entries/'.$entryI.'/Image'); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo $text['drive_image']; ?></label>
                                                        <input type="text" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="DriveImage" class="form-control cce-text"
                                                               value="<?php echo $config->getVals('GUI/Custom/Entries/'.$entryI.'/DriveImage'); ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 subtitle">
                                                    <?php echo $text['misc']; ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-4 cce-check-inline">
                                                    <?php foreach ($centryChkOpt as $cehk) {
                                                        $checkT = substr($cehk,5); ?>
                                                        <div class="checkbox" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>">
                                                            <label>
                                                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo $checkT; ?>" <?php echo isChecked($entry,$checkT); ?> /> <?php echo $text[$cehk]; ?>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="checkbox" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>">
                                                        <label>
                                                            <input type="checkbox" class="cce-checkbox" data-subid="<?php echo $entryI; ?>" data-field="FullTitle" <?php echo $fullTitleChecked; ?> /> <?php echo $text['full_title']; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label><?php echo $text['hidden']; ?></label>
                                                        <select class="cce-sel" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="Hidden">
                                                            <option value="" <?php echo isSelected(null, $hiddenVal); ?>></option>
                                                            <option value="false" <?php echo isSelected(false, $hiddenVal); ?>><?php echo $text['no']; ?></option>
                                                            <option value="true" <?php echo isSelected(true, $hiddenVal); ?>><?php echo $text['yes']; ?></option>
                                                            <option value="Always" <?php echo isSelected('Always', $hiddenVal); ?>><?php echo $text['always']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label><?php echo $text['inject_kext']; ?></label>
                                                        <select class="cce-sel" data-path="GUI/Custom/Entries/<?php echo $entryI; ?>" data-field="InjectKexts">
                                                            <option value="" <?php echo isSelected(null, $injectKextVal); ?>></option>
                                                            <option value="Detect" <?php echo isSelected('Detect', $injectKextVal); ?>><?php echo $text['detect']; ?></option>
                                                            <option value="true" <?php echo isSelected(true, $injectKextVal); ?>><?php echo $text['yes']; ?></option>
                                                            <option value="false" <?php echo isSelected(false, $injectKextVal); ?>><?php echo $text['no']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- col-md-4 here -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 subtitle">
                                                    <?php echo $text['sub_entries']; ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12" data-subid="<?php echo $entryI; ?>">
                                                    <?php echo drawPatchTable('subCEn',
                                                        array($text['title'], $text['add_arguments'], $text['full_title'], $text['common_settings']),
                                                        $config->getVals('GUI/Custom/Entries/'.$entryI.'/SubEntries'), array($entryI)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ++$entryI; } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['custom_legacy_entr']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('cLegE',
                array($text['volume'], $text['title'], $text['hotkey'], $text['full_title'],
                    $text['hidden'], $text['disabled'], $text['type']),
                $config->getVals('GUI/Custom/Legacy')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['custom_tool_entr']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('cToolE',
                array($text['volume'], $text['path'], $text['title'], $text['arguments'],
                    $text['hotkey'], $text['full_title'], $text['hidden'], $text['disabled']),
                $config->getVals('GUI/Custom/Tool')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['hide_vols']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('hideVl',
                        array($text['name']),
                        $config->getVals('GUI/Hide')); ?>
                </div>
            </div>
        </div>
        <!-- col-md-6 here -->
    </div>

<?php }
