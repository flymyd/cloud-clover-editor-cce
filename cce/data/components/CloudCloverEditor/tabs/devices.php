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

function devices() {
    global $text, $config;

    $audioInject = $config->getVals('Devices/Audio/Inject');
    $audioCombo = 'Detect,No';
    $usbO = $misc = $aud = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'usb_') $usbO[] = $k;
        if (substr($k,0,4) === 'dvc_') $misc[] = $k;
        if (substr($k,0,4) === 'aud_') $aud[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['fake_id']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['inj_Intel'].' GFX'; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="IntelGFX" value="<?php echo $config->getVals('Devices/FakeID/IntelGFX'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['inj_ATI']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="ATI" value="<?php echo $config->getVals('Devices/FakeID/ATI'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['inj_NVidia']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="NVidia" value="<?php echo $config->getVals('Devices/FakeID/NVidia'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['lan']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="LAN" value="<?php echo $config->getVals('Devices/FakeID/LAN'); ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['wifi']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="WIFI" value="<?php echo $config->getVals('Devices/FakeID/WIFI'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['sata']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="SATA" value="<?php echo $config->getVals('Devices/FakeID/SATA'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['xhci']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="XHCI" value="<?php echo $config->getVals('Devices/FakeID/XHCI'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['imei']; ?></label>
                <input type="text" class="form-control cce-text" data-path="Devices/FakeID" placeholder="Hex value" data-field="IMEI" value="<?php echo $config->getVals('Devices/FakeID/IMEI'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['devices']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4 cce-check-inline">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['usb']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach ($usbO as $usb) {
                        $checked = isChecked($config->getVals('Devices/USB/'.substr($usb,4))); ?>
                        <div class="checkbox" data-path="Devices/USB">
                            <label>
                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($usb,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$usb]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['audio']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-5">
                    <div class="form-group">
                        <label><?php echo $text['inject']; ?></label>
                        <input type="text" class="cce-combo" data-path="Devices/Audio" data-field="Inject"
                               placeholder="<?php echo $text['layout_id']; ?>" data-combo="<?php echo $audioCombo; ?>"
                               value="<?php echo $audioInject; ?>" pattern="[0-9]*" />
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-7 cce-check-inline">
                    <?php foreach ($aud as $audio) {
                        $checked = isChecked($config->getVals('Devices/Audio/'.substr($audio,4))); ?>
                        <div class="checkbox" data-path="Devices/Audio">
                            <label>
                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($audio,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$audio]; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4 cce-check-inline">
            <div class="row">
                <div class="col-md-12 subtitle">
                    <?php echo $text['misc']; ?>
                </div>
            </div>

            <?php foreach ($misc as $m) {
                $checked = isChecked($config->getVals('Devices/'.substr($m,4))); ?>
                <div class="checkbox" data-path="Devices">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($m,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$m]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['properties']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <textarea class="form-control cce-text" data-path="Devices" data-field="Properties" rows="3"><?php
                echo $config->getVals('Devices/Properties');
                ?></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle padtop-10">
            <?php echo $text['add_prop']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('addProp',
                array($text['device'],$text['key'],$text['value'], $text['comment']),
                $config->getVals('Devices/AddProperties')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['custom_props']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <?php echo drawPatchTable('cPropM',
                array($text['comment'],$text['pci_addr']),
                $config->getVals('Devices/Arbitrary')); ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <?php echo drawPatchTable('cPropS',
                array($text['key'],$text['value']),
                $config->getVals('Devices/Arbitrary/0/CustomProperties')); ?>
        </div>
    </div>

<?php }