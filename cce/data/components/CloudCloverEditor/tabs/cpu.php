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

function cpu() {
    global $text, $config;

    $hwpvalState = $config->getVals('CPU/HWPEnable') ? '':'disabled';
    $cpuSetCheck = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,5) === 'cpuC_') $cpuSetCheck[] = $k;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['cpu_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['cpu_frequency']; ?></label>
                <input type="text" data-path="CPU" data-field="FrequencyMHz" class="form-control cce-text" value="<?php echo $config->getVals('CPU/FrequencyMHz'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['bus_speed']; ?></label>
                <input type="text" data-path="CPU" data-field="BusSpeedkHz" class="form-control cce-text" value="<?php echo $config->getVals('CPU/BusSpeedkHz'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['qpi']; ?></label>
                <input type="text" data-path="CPU" data-field="QPI" class="form-control cce-text" value="<?php echo $config->getVals('CPU/QPI'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-sm"></div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['type']; ?></label>
                <input type="text" data-path="CPU" data-field="Type" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('CPU/Type'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['saving_mode']; ?></label>
                <input type="number" data-path="CPU" data-field="SavingMode" class="form-control cce-numb" value="<?php echo $config->getVals('CPU/SavingMode'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['hwp_value']; ?></label>
                <input type="text" data-path="CPU" data-field="HWPValue" <?php echo $hwpvalState; ?> class="form-control cce-text hwpval" placeholder="Hex" value="<?php echo $config->getVals('CPU/HWPValue'); ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['tdp_value']; ?></label>
                <input type="number" data-path="CPU" data-field="TDP" class="form-control cce-numb" value="<?php echo $config->getVals('CPU/TDP'); ?>" />
            </div>
        </div>
        <!-- col-md-10 here -->
    </div>
    <div class="row">
        <div class="col-md-12 cce-check-inline">
            <?php foreach ($cpuSetCheck as $check) {
                $checked = isChecked($config->getVals('CPU/'.substr($check,5))); ?>
                <div class="checkbox" data-path="CPU">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($check,5); ?>" <?php echo $checked; ?> /> <?php echo $text[$check]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }
