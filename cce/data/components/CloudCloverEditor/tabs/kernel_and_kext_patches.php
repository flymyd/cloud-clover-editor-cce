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

function kernelKextPatch() {
    global $text, $config;

    $patchesPanels = array(
        $config->getVals('KernelAndKextPatches/KextsToPatch'),
        $config->getVals('KernelAndKextPatches/KernelToPatch'),
        $config->getVals('KernelAndKextPatches/BootPatches')
    );

    $miscKK = $kextPopt = $kernPopt = $MatchosSupport = $bootEfiOpt = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'kkp_') $miscKK[] = $k;
        if (substr($k,0,5) === 'kopt_') $kextPopt[] = $k;
        if (substr($k,0,5) === 'krno_') $kernPopt[] = $k;
        if (substr($k,0,5) === 'befo_') $bootEfiOpt[] = $k;
        if (substr($k,0,4) === 'osv_') $MatchosSupport[$k] = $v;
    } ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['kernel_patch']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo $text['ati_con_data']; ?></label>
                <input type="text" data-path="KernelAndKextPatches" data-field="ATIConnectorsData" class="form-control cce-text" value="<?php echo $config->getVals('KernelAndKextPatches/ATIConnectorsData'); ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo $text['ati_con_patch']; ?></label>
                <input type="text" data-path="KernelAndKextPatches" data-field="ATIConnectorsPatch" class="form-control cce-text" value="<?php echo $config->getVals('KernelAndKextPatches/ATIConnectorsPatch'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['misc']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 cce-check-inline">
            <?php foreach ($miscKK as $kk) {
                $checked = isChecked($config->getVals('KernelAndKextPatches/'.substr($kk,4))); ?>
                <div class="checkbox" data-path="KernelAndKextPatches">
                    <label>
                        <input type="checkbox" class="cce-checkbox" data-field="<?php echo substr($kk,4); ?>" <?php echo $checked; ?> /> <?php echo $text[$kk]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['ati_con_controller']; ?></label>
                        <input type="text" data-path="KernelAndKextPatches" data-field="ATIConnectorsController" class="form-control cce-text" value="<?php echo $config->getVals('KernelAndKextPatches/ATIConnectorsController'); ?>" />
                    </div>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label><?php echo $text['fake_cpu_id']; ?></label>
                        <input type="text" data-path="KernelAndKextPatches" data-field="FakeCPUID" placeholder="Hex value" class="form-control cce-text" value="<?php echo $config->getVals('KernelAndKextPatches/FakeCPUID'); ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['kext_patch']; ?>
        </div>
    </div>

    <?php for ($i=0, $len=count($patchesPanels); $i<$len; ++$i) {
        $action = $patchesPanels[$i] == null ? 'workaround':'';
        $Idx = $action === 'workaround' ? '-':0;
        $type = $path = $opts = $title = ''; // kill warnings

        if ($i == 0) {
            $type = 'kextP';
            $path = 'KextsToPatch';
            $opts = $kextPopt;
            $title = 'kext_to_patch';
        } elseif ($i == 1) {
            $type = 'kernelP';
            $path = 'KernelToPatch';
            $opts = $kernPopt;
            $title = 'kernel_to_patch';
        } elseif ($i == 2) {
            $type = 'bootefiP';
            $path = 'BootPatches';
            $opts = $bootEfiOpt;
            $title = 'boot_efi_patches';
        } ?>
        <div class="row">
            <div class="col-md-12 subtitle">
                <?php echo $text[$title]; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div data-id="add-<?php echo $type; ?>" class="addTbBtn noDelBtn bg-green-flat single-item-add"><i class="fa icon-plus"></i> </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-white">
                            <div class="panel-body single-items-panel panel-<?php echo $type; ?>">
                                <?php
                                if ($patchesPanels[$i] == null)
                                    $patchesPanels[$i] = array('plc'); // workaround to generate the fist element

                                foreach ($patchesPanels[$i] as $elem) {
                                    $previewTitle = $i == 2 ? getPropVal($elem, 'Comment'):getPropVal($elem, 'Name');

                                    if ($action !== 'workaround') { ?>
                                        <div class="single-item single-<?php echo $type; ?>" data-toggle="modal" data-target=".modal<?php echo $type.'-'.$Idx; ?>">
                                            <div><i class="fa icon-gears single-item-icon"></i></div>
                                            <div class="single-item-title <?php echo $type; ?>-list-title" data-subid="<?php echo $Idx; ?>">
                                                <?php echo strlen($previewTitle) > 12 ? substr($previewTitle,0,12).'...':$previewTitle; ?>
                                            </div>
                                            <div>
                                                <button data-id="cp-cEntry" class="single-item-cp-btn bg-yellow-flat left copycEntry-btn" data-path="KernelAndKextPatches/<?php echo $path; ?>" data-index="<?php echo $Idx; ?>">
                                                    <i class="fa icon-files-o"></i>
                                                </button>
                                                <button data-id="del-<?php echo $type; ?>" class="single-item-del-btn delpatch-btn left" data-path="KernelAndKextPatches/<?php echo $path; ?>" data-index="<?php echo $Idx; ?>">
                                                    <i class="fa icon-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="modal fade <?php echo 'modal'.$type.'-'.$Idx; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title modal-<?php echo $type; ?>-title" data-subid="<?php echo $Idx; ?>"><?php echo $previewTitle; ?></h4>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="row">
                                                        <?php if ($i != 2) { // Boot Patches ?>
                                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo $text['name']; ?></label>
                                                                <input type="text" class="cce-text form-control single-item-title-upd <?php echo $type; ?>-name" data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>" data-field="Name" value="<?php echo $previewTitle; ?>" />
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12 subtitle text-center">
                                                                    <?php echo $text['options']; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 cce-check-inline">
                                                                    <?php foreach ($opts as $opt) {
                                                                        $optSub = substr($opt,5); ?>
                                                                        <div class="checkbox" data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>">
                                                                            <label>
                                                                                <input type="checkbox" class="cce-checkbox" data-field="<?php echo $optSub; ?>" <?php echo isChecked($elem, $optSub); ?> /> <?php echo $text[$opt]; ?>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo $text['find']; ?></label>
                                                                <textarea class="cce-text form-control" data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>"
                                                                          data-field="Find" rows="4"><?php echo getPropVal($elem, 'Find'); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo $text['replace']; ?></label>
                                                                <textarea class="cce-text form-control" data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>"
                                                                          data-field="Replace" rows="4"><?php echo getPropVal($elem, 'Replace'); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label><?php echo $text['comment']; ?></label>
                                                                <textarea class="cce-text form-control" data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>"
                                                                          data-field="Comment" rows="4"><?php echo getPropVal($elem, 'Comment'); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label><?php echo $text['match_oses']; ?></label>
                                                                        <select class="cce-sel matchoses">
                                                                            <option value=""></option>
                                                                            <option value="del"><?php echo $text['clear']; ?></option>
                                                                            <?php foreach ($MatchosSupport as $ver => $os) { ?>
                                                                                <option value="<?php echo substr($ver,4); ?>"><?php echo $os; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                <textarea class="form-control cce-text matchos-tx" rows="3"
                                                                          data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>"
                                                                          data-field="MatchOS"><?php echo getPropVal($elem, 'MatchOS'); ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label><?php echo $text['match_build'] ?></label>
                                                                <textarea class="form-control cce-text matchbuild-texarea" rows="5"
                                                                          data-path="KernelAndKextPatches/<?php echo $path.'/'.$Idx; ?>"
                                                                          data-field="MatchBuild"><?php echo getPropVal($elem, 'MatchBuild'); ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php ++$Idx; } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-12 subtitle">
            <?php echo $text['force_kext']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php drawPatchTable('forceKx',
                array($text['name']),
                $config->getVals('KernelAndKextPatches/ForceKextsToLoad')); ?>
        </div>
    </div>

<?php }