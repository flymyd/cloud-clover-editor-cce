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

function disDrv() {
    global $text, $config; ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['disdrv_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php echo drawPatchTable('disaDrv', array($text['driver']),
                $config->getVals('DisableDrivers')); ?>
        </div>
    </div>
<?php }