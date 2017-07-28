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

function gfx() {
    global $text, $config;

    $inject = $config->getVals('Graphics/Inject');
    $dualLink = $config->getVals('Graphics/DualLink');
    $edidOpt = $toInj = $gfxOpt = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'edd_') $edidOpt[] = $k;
        if (substr($k,0,4) === 'inj_') $toInj[] = $k;
        if (substr($k,0,4) === 'gfc_') $gfxOpt[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['edid_patch']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-2 cce-check-inline">
            <?php foreach ($edidOpt as $edid) {
                $checked = isChecked($config->getVals('Graphics/EDID/'.substr($edid,4))); ?>
                <div class="checkbox" data-path="Graphics/EDID">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($edid,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$edid]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['edid_vendor_id']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics/EDID" data-field="VendorID" placeholder="0x1006" value="<?php echo $config->getVals('Graphics/EDID/VendorID'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['edid_product_id']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics/EDID" data-field="ProductID" placeholder="0x9221" value="<?php echo $config->getVals('Graphics/EDID/ProductID'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['custom_edid']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <textarea rows="3" class="form-control cce-text" data-path="Graphics/EDID" data-field="Custom"><?php
                echo $config->getVals('Graphics/EDID/Custom');
                ?></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['gfx_patch']; ?>
        </div>
    </div>

    <div class="row padtop-10">
        <div class="col-xs-6 col-sm-4 col-md-4">
            <div class="form-group">
                <label><?php echo $text['ig_platform_id']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics" data-field="ig-platform-id" placeholder="Hex value" value="<?php echo $config->getVals('Graphics/ig-platform-id'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-4">
            <div class="form-group">
                <label><?php echo $text['snb_platform_id']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics" data-field="snb-platform-id" placeholder="Hex value" value="<?php echo $config->getVals('Graphics/snb-platform-id'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="form-group">
                <label><?php echo $text['display_cfg']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics" data-field="display-cfg" value="<?php echo $config->getVals('Graphics/display-cfg'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-9 col-sm-6 col-md-6">
            <div class="form-group">
                <label><?php echo $text['nvcap']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Graphics" data-field="NVCAP" value="<?php echo $config->getVals('Graphics/NVCAP'); ?>" />
            </div>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-1">
            <div class="form-group">
                <label><?php echo $text['dual_link']; ?></label>
                <select class="cce-sel dualLink" data-path="Graphics" data-field="DualLink">
                    <option value=""></option>
                    <option value="0" <?php echo isSelected($dualLink, 0); ?>>0</option>
                    <option value="1" <?php echo isSelected($dualLink, 1); ?>>1</option>
                </select>
            </div>
        </div>
        <!-- col-md-5 here -->
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-3">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a>
                        <div class="checkbox" data-path="Graphics">
                            <label>
                                <input type="checkbox" class="cce-checkbox inject_gfx" data-field="Inject" <?php echo isChecked($inject); ?> /> <?php echo $text['inject']; ?>
                            </label>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content single_inj cce-check-inline">
                <div class="tab-pane active fixes-tab-bg">
                    <?php foreach ($toInj as $inj) {
                        $checked = '';
                        $disabled = $inject === true ? 'disabled':'';
                        $labelDis = $inject === true ? ' class="color-disabled"':'';
                        if ($inject !== true) {
                            $checked = isChecked($config->getVals('Graphics/Inject/'.substr($inj,4)));
                        } ?>
                        <div class="checkbox" data-path="Graphics/Inject">
                            <label<?php echo $labelDis; ?>>
                                <input type="checkbox" class="cce-checkbox" <?php echo $disabled; ?> data-field="<?php echo substr($inj,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$inj]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-12 col-md-9 padtop-mob">
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-2">
                    <div class="form-group">
                        <label><?php echo $text['vram']; ?></label>
                        <input type="text" class="form-control cce-text" data-path="Graphics" data-field="VRAM" value="<?php echo $config->getVals('Graphics/VRAM'); ?>" />
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2">
                    <div class="form-group">
                        <label><?php echo $text['vports']; ?></label>
                        <input type="text" class="form-control cce-text" data-path="Graphics" data-field="VideoPorts" value="<?php echo $config->getVals('Graphics/VideoPorts'); ?>" />
                    </div>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-xs-6 col-sm-3 col-md-2">
                    <div class="form-group">
                        <label><?php echo $text['ref_clk']; ?></label>
                        <input type="text" class="form-control cce-text" data-path="Graphics" data-field="RefCLK" value="<?php echo $config->getVals('Graphics/RefCLK'); ?>" />
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2">
                    <div class="form-group">
                        <label><?php echo $text['boot_display']; ?></label>
                        <input type="text" class="form-control cce-text" data-path="Graphics" data-field="BootDisplay" value="<?php echo $config->getVals('Graphics/BootDisplay'); ?>" />
                    </div>
                </div>
                <div class="clearfix visible-xs visible-sm"></div>
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <label><?php echo $text['fb_name']; ?></label>
                        <input type="text" class="form-control cce-text" data-path="Graphics" data-field="FBName" value="<?php echo $config->getVals('Graphics/FBName'); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 cce-check-inline">
                    <?php foreach ($gfxOpt as $gfx) {
                        $checked = isChecked($config->getVals('Graphics/'.substr($gfx,4))); ?>
                        <div class="checkbox" data-path="Graphics">
                            <label>
                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($gfx,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$gfx]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['vbios_patch']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('vbiosP',
                array($text['find'], $text['replace']),
                $config->getVals('Graphics/PatchVBiosBytes')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['mult_gfx_card_injection']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            NVidia
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('mNvI',
                array($text['model'], $text['iopci_primary_match'], $text['iopci_sub_dev_id'],
                    $text['vram'], $text['vports'], $text['gfc_LoadVBios']),
                $config->getVals('Graphics/NVIDIA')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            ATI
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('mAI',
                array($text['model'], $text['iopci_primary_match'], $text['iopci_sub_dev_id'],
                    $text['vram']),
                $config->getVals('Graphics/ATI')); ?>
        </div>
    </div>

<?php }