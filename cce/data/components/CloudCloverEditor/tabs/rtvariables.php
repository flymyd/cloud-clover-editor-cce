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

function rtVariables() {
    global $text, $config;

    $romOpts = 'UseMacAddr0,UseMacAddr1';
    $booterOpts = '0x28';
    $csrOpts = '0x0,0x3,0x14,0x67,0x127';
    $isOz = isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) && $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'oz' ? true:false;
    $hiddenEl = $isOz ? 'hidden-el':'';
    $currentCSR = $isOz ? $config->getVals('Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82/csr-active-config'):$config->getVals('RtVariables/CsrActiveConfig');
    $currentBooter = $config->getVals('RtVariables/BooterConfig');
    $csrBits = getFlagBits($currentCSR);
    $booterBits = getFlagBits($currentBooter);
    $sipF = $btrF = array();

    if ($isOz) {
        if ($currentCSR != null && substr($currentCSR,0,2) != '0x')
            $currentCSR = '0x'.$currentCSR;

        $csrPath = 'csr-active-config';
        $dataPath = 'Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82';
    } else {
        $csrPath = 'CsrActiveConfig';
        $dataPath = 'RtVariables';
    }

    foreach ($text as $k => $v) {
        if (substr($k,0,5) === 'csrf_') $sipF[] = $v;
        if (substr($k,0,4) === 'btr_') $btrF[] = $v;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['rt_var_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 nooz <?php echo $hiddenEl; ?>">
            <div class="form-group">
                <label><?php echo $text['mlb']; ?></label>
                <input type="text" class="cce-text form-control" value="<?php echo $config->getVals('RtVariables/MLB'); ?>" data-path="RtVariables" data-field="MLB" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 nooz <?php echo $hiddenEl; ?>">
            <div class="form-group">
                <label><?php echo $text['rom']; ?></label>
                <input type="text" class="cce-combo" value="<?php echo $config->getVals('RtVariables/ROM'); ?>"
                       data-path="RtVariables" data-field="ROM" data-combo="<?php echo $romOpts; ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-6 col-sm-4 col-md-2 nooz <?php echo $hiddenEl; ?>">
            <div class="form-group">
                <label><?php echo $text['booter_config']; ?></label>
                <div class="input-group">
                    <input type="text" disabled class="cce-combo combobox-input-group" value="<?php echo $currentBooter; ?>"
                           data-path="RtVariables" data-field="BooterConfig" data-combo="<?php echo $booterOpts; ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default combobox-input-group-btn" type="button" data-toggle="modal" data-target=".booter-modal"><i class="fa icon-gear"></i> </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-4 col-sm-offset-4 col-md-2 col-md-offset-0">
            <div class="form-group">
                <label><?php echo $text['csr_config']; ?></label>
                <div class="input-group">
                    <input type="text" disabled class="cce-combo combobox-input-group to-oz-rt-path csrActiveConf" value="<?php echo $currentCSR; ?>"
                           data-path="<?php echo $dataPath; ?>" data-field="<?php echo $csrPath; ?>" data-combo="<?php echo $csrOpts; ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default combobox-input-group-btn" type="button" data-toggle="modal" data-target=".csr-modal"><i class="fa icon-gear"></i> </button>
                    </span>
                </div>
            </div>
        </div>
        <!-- col-md-2 here -->
    </div>

    <!-- CSR Modal -->
    <div class="modal fade csr-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $text['csr_modal_title']; ?> <span class="csr-val">[ <span class="fflag"><?php echo $currentCSR; ?></span> ]</span> </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php for ($i=0, $len=count($sipF); $i<$len; ++$i) {
                                $checked = isBitSelected($i, $csrBits) ? 'checked':''; ?>
                                <div class="checkbox sip-flag">
                                    <label>
                                        <input type="checkbox" class="cce-checkbox sip-flag-val" value="<?php echo $i; ?>" <?php echo $checked; ?> /> <?php echo $sipF[$i]; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booter Modal -->
    <div class="modal fade booter-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $text['booter_modal_title']; ?> <span class="btr-val">[ <span class="fbflag"><?php echo $currentBooter; ?></span> ]</span> </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php for ($i=0, $len=count($btrF); $i<$len; ++$i) {
                                $checked = isBitSelected($i, $booterBits) ? 'checked':''; ?>
                                <div class="checkbox btr-flag">
                                    <label>
                                        <input type="checkbox" class="cce-checkbox btr-flag-val" value="<?php echo $i; ?>" <?php echo $checked; ?> /> <?php echo $btrF[$i]; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }
