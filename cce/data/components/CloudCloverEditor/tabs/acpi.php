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

function acpi() {
    global $text, $config;

    $droppedDsm = $config->getVals('ACPI/DSDT/DropOEM_DSM');
    $fixMask = $config->getVals('ACPI/DSDT/FixMask');
    $generate = $config->getVals('ACPI/SSDT/Generate');
    $dsdtFixesList = $dropped = $miscDsdt = $acpiOpt = $ssdtOpt = $ssel = $stx = array();
    $maskDis = '';

    foreach ($text as $k => $v) {
        if (substr($k,0,5) === 'dfix_') $dsdtFixesList[] = $k;
        if (substr($k,0,2) === 'd_') $dropped[] = $k;
        if (substr($k,0,2) === 'm_') $miscDsdt[] = $k;
        if (substr($k,0,2) === 'a_') $acpiOpt[] = $k;
        if (substr($k,0,3) === 'ss_') $ssdtOpt[] = $k;
        if (substr($k,0,5) === 'ssel_') $ssel[] = $k;
        if (substr($k,0,4) === 'stx_') $stx[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['dsdt']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['patches']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('dsdtP',
                array($text['comment'], $text['find'], $text['replace'], $text['disabled']),
                $config->getVals('ACPI/DSDT/Patches') ); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['fixes']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tab-content cce-check-inline">
                <div id="dsdt-fixes" class="tab-pane active fixes-tab-bg">
                    <?php foreach ($dsdtFixesList as $fix) {
                        $checked = isChecked($config->getVals('ACPI/DSDT/Fixes/'.substr($fix,5)));
                        if ($maskDis === '' && $checked === 'checked') { $maskDis = 'disabled'; } ?>
                        <div class="checkbox" data-path="ACPI/DSDT/Fixes">
                            <label>
                                <input type="checkbox" class="cce-checkbox dsdtfix-c" data-field="<?php echo substr($fix,5); ?>" <?php echo $checked; ?> /> <?php echo $text[$fix]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle padtop-10">
            <?php echo $text['misc']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-8">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a>
                        <div class="checkbox" data-path="ACPI/DSDT">
                            <label>
                                <input type="checkbox" class="cce-checkbox drop_dsm" data-field="DropOEM_DSM" <?php echo isChecked($droppedDsm); ?> /> <?php echo $text['drop_dsm']; ?>
                            </label>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content single_dsm cce-check-inline">
                <div class="tab-pane active fixes-tab-bg">
                    <?php foreach ($dropped as $drop) {
                        $checked = '';
                        $disabled = $droppedDsm === true ? 'disabled':'';
                        $labelDis = $droppedDsm === true ? ' class="color-disabled"':'';
                        // droppedDsm is not an array when its value is true or false, kill some warnings!
                        if (!is_bool($droppedDsm)) {
                            $checked = isChecked($config->getVals('ACPI/DSDT/DropOEM_DSM/'.substr($drop,2)));
                        } ?>
                        <div class="checkbox" data-path="ACPI/DSDT/DropOEM_DSM">
                            <label<?php echo $labelDis; ?>>
                                <input type="checkbox" class="cce-checkbox" <?php echo $disabled; ?> data-field="<?php echo substr($drop,2); ?>" <?php echo $checked; ?> /> <?php echo $text[$drop]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-12 col-sm-5 col-md-4 miscDsdt cce-check-inline">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['dsdt_name']; ?></label>
                        <input type="text" data-path="ACPI/DSDT" data-field="Name" class="form-control cce-text" placeholder="DSDT.aml" value="<?php echo $config->getVals('ACPI/DSDT/Name'); ?>" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 padtop-mob">
                    <div class="form-group">
                        <label><?php echo $text['fix_mask']; ?></label>
                        <input id="manual_fixmask" type="text" data-path="ACPI/DSDT" data-field="FixMask" <?php echo $maskDis; ?> class="form-control cce-text" value="<?php echo $maskDis === '' ? $fixMask:''; ?>" />
                    </div>
                </div>
            </div>
            <?php foreach ($miscDsdt as $miscOpt) {
                $checked = isChecked($config->getVals('ACPI/DSDT/'.substr($miscOpt,2))); ?>
                <div class="checkbox" data-path="ACPI/DSDT">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($miscOpt,2); ?>" <?php echo $checked; ?> /> <?php echo $text[$miscOpt]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['ssdt']; ?>
        </div>
    </div>

    <div class="row ssdtOpt cce-check-inline">
        <div class="col-xs-12 col-sm-12 col-md-7">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a>
                        <div class="checkbox tab-generate" data-path="ACPI/SSDT">
                            <label>
                                <input type="checkbox" class="cce-checkbox ssdt-gen" data-field="Generate" <?php echo isChecked($generate); ?> /> <?php echo $text['generate']; ?>
                            </label>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active fixes-tab-bg single_ssdt">
                    <?php foreach ($ssdtOpt as $ssdt) {
                        $disabled = $labelDis = '';
                        $ssdtVal = substr($ssdt,3);
                        $isState = $ssdtVal === 'CStates' || $ssdtVal === 'PStates' ? '/Generate/':'';

                        if ($generate === true && ($ssdtVal === 'CStates' || $ssdtVal === 'PStates')) {
                            $checked = '';
                            $disabled = 'disabled';
                            $labelDis = 'class="color-disabled"';
                        } elseif ($generate === false && ($ssdtVal === 'CStates' || $ssdtVal === 'PStates')) {
                            // Same as droppedDsm, kill warnings
                            $checked = '';
                        } else {
                            $checked = isChecked($config->getVals('ACPI/SSDT/'.substr($isState,1).$ssdtVal));
                        } ?>
                        <div class="checkbox" data-path="ACPI/SSDT<?php echo substr($isState,0,-1); ?>">
                            <label <?php echo $labelDis; ?>>
                                <input type="checkbox" class="cce-checkbox" <?php echo $disabled; ?> data-field="<?php echo $ssdtVal; ?>" <?php echo $checked; ?> /> <?php echo $text[$ssdt]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 padtop-mob">
            <div class="row">
                <div class="col-xs-6 col-sm-12 col-md-12">
                    <div class="row">
                        <?php $max = 2;
                        foreach ($ssel as $sel) {
                            $selVal = substr($sel,5);
                            $curValue = $config->getVals('ACPI/SSDT/'.$selVal);

                            if ($selVal === 'PLimitDict') $max = 3;
                            if ($selVal === 'UnderVoltStep') $max = 10; ?>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $text[$sel]; ?></label>
                                    <select data-path="ACPI/SSDT" data-field="<?php echo $selVal; ?>" class="cce-sel">
                                        <option value=""></option>
                                        <?php for ($i=0; $i<$max; ++$i) { ?>
                                            <option value="<?php echo $i; ?>" <?php echo isSelected($curValue, $i); ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-12 col-md-12 lineHieghtMob">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['min_mult']; ?></label>
                                <input type="text" data-path="ACPI/SSDT" data-field="MinMultiplier" class="form-control cce-text" value="<?php echo $config->getVals('ACPI/SSDT/MinMultiplier'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['max_mult']; ?></label>
                                <input type="text" data-path="ACPI/SSDT" data-field="MaxMultiplier" class="form-control cce-text" value="<?php echo $config->getVals('ACPI/SSDT/MaxMultiplier'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><?php echo $text['c3_latency']; ?></label>
                                <input type="text" data-path="ACPI/SSDT" data-field="C3Latency" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('ACPI/SSDT/C3Latency'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['acpi']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['sort_order']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('ssdtOr',
                        array($text['name']),
                        $config->getVals('ACPI/SortedOrder')); ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['sort_comment']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <textarea class="form-control cce-text margbot-mob" rows="5" data-path="ACPI" data-field="SortedOrder-Comment"><?php
                        echo $config->getVals('ACPI/SortedOrder-Comment');
                        ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['drop_tables']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('ssdtTb',
                        array($text['signature'],$text['k_type'],$text['str_numb']),
                        $config->getVals('ACPI/DropTables')); ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['dis_aml']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo drawPatchTable('disaAml',array($text['name']),
                        $config->getVals('ACPI/DisabledAML')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['ResetAddress']; ?></label>
                        <input type="text" data-path="ACPI" data-field="ResetAddress" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('ACPI/ResetAddress'); ?>">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['ResetValue']; ?></label>
                        <input type="text" data-path="ACPI" data-field="ResetValue" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('ACPI/ResetValue'); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 cce-check-inline">
                    <?php foreach ($acpiOpt as $acpi) {
                        $checked = isChecked($config->getVals('ACPI/'.substr($acpi,2))); ?>
                        <div class="checkbox" data-path="ACPI">
                            <label>
                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($acpi,2); ?>" <?php echo $checked; ?> /> <?php echo $text[$acpi]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- col-md-6 here -->
    </div>

<?php }