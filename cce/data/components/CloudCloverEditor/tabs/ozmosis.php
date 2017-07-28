<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

function ozmosis() {
    global $text, $config;

    $voodoHdaChecked = isChecked($config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/DisableVoodooHda'));
    $voodoHdaSpidfChecked = isChecked($config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/EnableVoodooHdaInternalSpdif'));
    $intelInjDisabled = isChecked($config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/DisableIntelInjection')) ? 'disabled':'';
    $atiInjDisabled = isChecked($config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/DisableAtiInjection')) ? 'disabled':'';
    $curAcpiLoaderFlag = $config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/AcpiLoaderMode');
    $acpiLoaderOpts = '0x0,0x45,0x47';
    $acpiLoaderBitsSum = 0;
    $gfxOpts = $bootArg = $acpiFlags = $diskOpts = $templateOpts = array();

    foreach ($text as $k => $v) {
        if (substr($k,0,4) === 'ozg_')    $gfxOpts[] = $k;
        if (substr($k, 0, 5) === 'barg_') $bootArg[$k] = $v;
        if (substr($k, 0, 4) === 'ozt_') $templateOpts[] = $v;
        if (substr($k, 0, 5) === 'ozdt_') $diskOpts[] = $k;

        if (substr($k, 0, 5) === 'ozal_') {
            $acpiFlags[$k] = $v;
            $acpiLoaderBitsSum |= intval(substr($k, 5), 16);
        }
    }

    $acpiBits = getAcpiLoaderFlagBits($curAcpiLoaderFlag, $acpiLoaderBitsSum, $acpiFlags);
    ?>
    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['ozmosis_title']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['oz_smbios_sec']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 see-more"><?php echo $text['oz_smbios_see_more']; ?></div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_sys_sku']; ?></label>
                <input type="text" data-path="Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102" data-field="SystemSKU" class="form-control cce-text" value="<?php echo $config->getVals('Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102/SystemSKU'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_fw_rev']; ?></label>
                <input type="text" data-path="Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102" data-field="FirmwareRevision" class="form-control cce-text" value="<?php echo $config->getVals('Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102/FirmwareRevision'); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_board_ass_tag']; ?></label>
                <input type="text" data-path="Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102" data-field="BaseBoardAssetTag" class="form-control cce-text" value="<?php echo $config->getVals('Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102/BaseBoardAssetTag'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_hw_addr']; ?></label>
                <input type="text" data-path="Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102" data-field="HardwareAddress" class="form-control cce-text oz-mac" placeholder="Your MAC address" value="<?php echo $config->getVals('Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102/HardwareAddress'); ?>" />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['oz_gfx_sec']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['oz_ati_fb']; ?></label>
                <input type="text" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101" data-field="AtiFramebuffer" <?php echo $atiInjDisabled; ?> class="form-control cce-text ozati-inj" value="<?php echo $config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/AtiFramebuffer'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_snb_pid']; ?></label>
                <input type="text" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101" data-field="AAPL,snb_platform_id" <?php echo $intelInjDisabled; ?> class="form-control cce-text ozintel-inj" value="<?php echo $config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/AAPL,snb_platform_id'); ?>" />
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="form-group">
                <label><?php echo $text['oz_ig_pid']; ?></label>
                <input type="text" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101" data-field="AAPL,ig-platform-id" <?php echo $intelInjDisabled; ?> class="form-control cce-text ozintel-inj" value="<?php echo $config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/AAPL,ig-platform-id'); ?>" />
            </div>
        </div>

        <div class="clearfix visible-sm"></div>

        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['oz_acpi_loader']; ?></label>
                <div class="input-group">
                    <input type="text" disabled class="cce-combo combobox-input-group" value="<?php echo $curAcpiLoaderFlag; ?>"
                           data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101" data-field="AcpiLoaderMode" data-combo="<?php echo $acpiLoaderOpts; ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default combobox-input-group-btn" type="button" data-toggle="modal" data-target=".acpiload-modal"><i class="fa icon-gear"></i> </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="form-group">
                <label><?php echo $text['timeout']; ?></label>
                <input type="number" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101" data-field="TimeOut" class="form-control cce-numb" value="<?php echo $config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/TimeOut'); ?>" />
            </div>
        </div>
    </div>

    <div class="row margin-top-15">
        <div class="col-md-12">

            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="row">
                        <div class="col-md-12 subtitle">
                            <?php echo $text['oz_disk_title'] ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="cce-sel select-pad oz-disk-type">
                                    <option value=""></option>
                                    <?php foreach ($diskOpts as $opt) { ?>
                                        <option value="<?php echo substr($opt, 5); ?>"><?php echo $text[$opt]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="row">
                        <div class="col-md-12 subtitle">
                            <?php echo $text['oz_templ_title'] ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="cce-sel select-pad oz-templ-type">
                                    <option value=""></option>
                                    <option value="del"><?php echo $text['oz_clear_template']; ?></option>
                                    <?php foreach ($templateOpts as $opt) { ?>
                                        <option value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- col-md-6 here -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea class="form-control cce-text oz-templates-tx" rows="4" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101"
                                  data-field=""></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 cce-check-inline">
            <?php foreach ($gfxOpts as $ozOp) {
                $current = substr($ozOp,4);
                $checked = isChecked($config->getVals('Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101/'.$current));
                $disabled = ($current == 'DisableVoodooHda' && $voodoHdaSpidfChecked) || ($current == 'EnableVoodooHdaInternalSpdif' && $voodoHdaChecked) ? 'disabled':''; ?>
                <div class="checkbox" data-path="Defaults:1F8E0C02-58A9-4E34-AE22-2B63745FA101">
                    <label>
                        <input type="checkbox" class="cce-checkbox" <?php echo $disabled; ?> data-field="<?php echo $current; ?>" <?php echo $checked; ?> /> <?php echo $text[$ozOp]; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 title">
            <?php echo $text['oz_boot_arg_sec']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 see-more"><?php echo $text['oz_boot_see_more']; ?></div>
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
                        <select class="cce-sel b-args arg-oz select-pad">
                            <option value=""></option>
                            <option value="del"><?php echo $text['clear'].' '.$text['boot_arg']; ?></option>
                            <?php foreach ($bootArg as $arg => $desc) { ?>
                                <option value="<?php echo substr($arg, 5); ?>"><?php echo $desc; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea class="form-control cce-text b-args-tx barg-ozmosis" rows="3" data-path="Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82"
                                  data-field="boot-args"><?php echo $config->getVals('Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82/boot-args'); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- col-md-6 here -->
    </div>

    <!-- ACPI Loader Mode Modal -->
    <div class="modal fade acpiload-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo $text['oz_acpi_loader_modal_title']; ?> <span class="ozal-val">[ <span class="ozalflag"><?php echo $curAcpiLoaderFlag; ?></span> ]</span> </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach ($acpiFlags as $key => $aFlag) {
                                $flagSub = substr($key, 5);
                                $checked = ($curAcpiLoaderFlag == '0x0' && $flagSub == 0) || (isBitSelected($flagSub, $acpiBits)) ? 'checked':'';
                                $disabled = $curAcpiLoaderFlag == '0x0' && $flagSub != 0 ? 'disabled':''; ?>
                                <div class="checkbox ozal-flag">
                                    <label>
                                        <input type="checkbox" class="cce-checkbox ozal-flag-val" <?php echo $checked.' '.$disabled; ?> value="<?php echo $flagSub; ?>" /> <?php echo $text[$key]; ?>
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