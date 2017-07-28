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

function sysparam() {
    global $text, $config;

    $injkext = $config->getVals('SystemParameters/InjectKexts');
    $sysParam = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'syp_') $sysParam[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['sysparam_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5">
            <div class="form-group">
                <label><?php echo $text['custom_uuid']; ?></label>
                <input type="text" data-path="SystemParameters" data-field="CustomUUID" class="form-control cce-text" value="<?php echo $config->getVals('SystemParameters/CustomUUID'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['light_lev']; ?></label>
                <input type="text" data-path="SystemParameters" data-field="BacklightLevel" class="form-control cce-text" value="<?php echo $config->getVals('SystemParameters/BacklightLevel'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <div class="form-group">
                <label><?php echo $text['inject_kext']; ?></label>
                <select data-path="SystemParameters" data-field="InjectKexts" class="cce-sel">
                    <option value=""></option>
                    <option value="true" <?php echo isSelected(true, $injkext); ?>><?php echo $text['yes']; ?></option>
                    <option value="false" <?php echo isSelected(false, $injkext); ?>><?php echo $text['no']; ?></option>
                    <option value="Detect" <?php echo isSelected('Detect', $injkext); ?>><?php echo $text['detect']; ?></option>
                </select>
            </div>
        </div>
        <!-- col-md-3 here -->
    </div>

    <div class="row">
        <div class="col-md-12 cce-check-inline">
            <?php foreach ($sysParam as $check) {
                $checked = isChecked($config->getVals('SystemParameters/'.substr($check,4))); ?>
                <div class="checkbox" data-path="SystemParameters">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($check,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$check]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>

<?php }