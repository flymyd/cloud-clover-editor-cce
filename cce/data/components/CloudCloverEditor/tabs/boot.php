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

function boot() {
    global $text, $config;

    $xmpCombo = 'Yes,No,0,1,2';

    $bootVol = $config->getVals('Boot/DefaultVolume');
    $legacyVal = $config->getVals('Boot/Legacy');
    $xmp = $config->getVals('Boot/XMPDetection');
    $policyVal = $config->getVals('Boot/Policy');
    $cLogoO = '';
    $secPol = $bootO = $legacyBootO = $bootArg = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'osl_') $cLogoO .= $v.',';
        if (substr($k,0,4) === 'sec_') $secPol[] = $k;
        if (substr($k,0,3) === 'bb_') $bootO[] = $k;
        if (substr($k,0,4) === 'lbo_') $legacyBootO[] = $k;
        if (substr($k,0,5) === 'barg_') $bootArg[$k] = $v;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['boot_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['boot_arg']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <select class="cce-sel b-args select-pad">
                            <option value=""></option>
                            <option value="del"><?php echo $text['clear'].' '.$text['boot_arg']; ?></option>
                            <?php foreach ($bootArg as $arg => $desc) { ?>
                                <option value="<?php echo substr($arg,5); ?>"><?php echo $desc; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea class="form-control cce-text b-args-tx" rows="3" data-path="Boot" data-field="Arguments"><?php
                            echo $config->getVals('Boot/Arguments');
                            ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-12 col-md-8">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['misc']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['def_b_vol']; ?></label>
                                <input class="cce-combo" type="text" value="<?php echo $bootVol; ?>" data-path="Boot" data-field="DefaultVolume" data-combo="LastBootedVolume" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['timeout']; ?></label>
                                <input type="number" data-path="Boot" data-field="Timeout" min="-1" class="form-control cce-numb" value="<?php echo $config->getVals('Boot/Timeout'); ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['def_loader']; ?></label>
                                <input type="text" data-path="Boot" data-field="DefaultLoader" placeholder="boot.efi" class="form-control cce-text" value="<?php echo $config->getVals('Boot/DefaultLoader'); ?>" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['legacy']; ?></label>
                                <select class="cce-sel" data-path="Boot" data-field="Legacy">
                                    <option value=""></option>
                                    <?php foreach ($legacyBootO as $lbo) { ?>
                                    <option value="<?php echo substr($lbo,4); ?>" <?php echo isSelected(substr($lbo,4), $legacyVal); ?>><?php echo $text[$lbo]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['os_logo']; ?></label>
                                <input type="text" class="cce-combo" data-path="Boot" data-field="CustomLogo"
                                       data-combo="<?php echo $cLogoO; ?>" placeholder="<?php echo $text['c_logo_placeholder']; ?>"
                                       value="<?php echo boolToText($config->getVals('Boot/CustomLogo')); ?>" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['boot_bg_color']; ?></label>
                                <input type="text" data-path="Boot" data-field="BootBgColor" class="form-control cce-text" value="<?php echo $config->getVals('Boot/BootBgColor'); ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo $text['xmp_det']; ?></label>
                                <input type="text" class="cce-combo" data-path="Boot" data-field="XMPDetection" data-combo="<?php echo $xmpCombo; ?>"
                                       value="<?php echo boolToText($xmp); ?>" />
                            </div>
                        </div>
                        <!-- col-md-6 here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 cce-check-inline">
            <?php foreach ($bootO as $bOpt) {
                $checked = isChecked($config->getVals('Boot/'.substr($bOpt,3))); ?>
                <div class="checkbox" data-path="Boot">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($bOpt,3); ?>" <?php echo $checked; ?> /> <?php echo $text[$bOpt]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['secure_boot']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['whitelist']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('whiteB',
                        array($text['name']),
                        $config->getVals('Boot/WhiteList')); ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 padtop-xs">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['blacklist']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('blackB',
                        array($text['name']),
                        $config->getVals('Boot/BlackList')); ?>
                </div>
            </div>
        </div>
        <div class="clearfix visible-sm"></div>
        <div class="col-xs-12 col-sm-12 col-md-2 padtop-mob">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-12">
                    <div class="checkbox" data-path="Boot">
                        <label>
                            <input type="checkbox" class="cce-checkbox" data-field="Secure" <?php echo isChecked($config->getVals('Boot/Secure')); ?> /> <?php echo $text['secure']; ?>
                        </label>
                    </div>
                </div>
                <div class="clearfix visible-md"></div>
                <div class="col-xs-6 col-sm-6 col-md-12">
                    <div class="form-group">
                        <label><?php echo $text['secure_policy']; ?></label>
                        <select class="cce-sel clogo" data-path="Boot" data-field="Policy">
                            <option value=""></option>
                            <?php foreach ($secPol as $policy) {
                                $currentPolicyV = substr($policy,4); ?>
                                <option value="<?php echo $currentPolicyV; ?>" <?php echo isSelected($currentPolicyV, $policyVal); ?>><?php echo $text[$policy]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }