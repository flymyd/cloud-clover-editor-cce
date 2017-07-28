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

function bootGfx() {
    global $text, $config; ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['bootgfx_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-3">
            <div class="form-group">
                <label><?php echo $text['def_bg_color']; ?></label>
                <input type="text" data-path="BootGraphics" data-field="DefaultBackgroundColor" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('BootGraphics/DefaultBackgroundColor'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-6 col-sm-3 col-md-2">
            <div class="form-group">
                <label><?php echo $text['ui_scale']; ?></label>
                <input type="number" data-path="BootGraphics" data-field="UIScale" class="form-control cce-numb" value="<?php echo $config->getVals('BootGraphics/UIScale'); ?>" />
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['efi_login_hi_dpi']; ?></label>
                <input type="number" data-path="BootGraphics" data-field="EFILoginHiDPI" class="form-control cce-numb" value="<?php echo $config->getVals('BootGraphics/EFILoginHiDPI'); ?>" />
            </div>
        </div>
        <div class="clearfix visible-xs visible-sm"></div>
        <div class="col-xs-12 col-sm-5 col-md-3">
            <div class="form-group">
                <label><?php echo $text['flagstate']; ?></label>
                <input type="text" data-path="BootGraphics" data-field="flagstate" class="form-control cce-text" value="<?php echo $config->getVals('BootGraphics/flagstate'); ?>" />
            </div>
        </div>
        <!-- col-md-2 here -->
    </div>

<?php }