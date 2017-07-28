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

function cce() {
    global $text, $config;

    $upgradable = $config->checkUpgrade();
    $cceSettHexChecked = 'checked';
    $cceSettHb64Checked = '';

    if (isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64']) &&
        $_SESSION['cce-sett'][$_SESSION['cur_idx']]['hb64'] == 'b64') {
        $cceSettHexChecked = '';
        $cceSettHb64Checked = 'checked';
    } ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <img src="../style/img/cce-logo.png" class="img-responsive cce-ccelogo" /> <p class="cce-logotx">Cloud Clover Editor</p>
        </div>
    </div>

    <?php if ($upgradable === true) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="upgrade-warning text-center">
                <?php echo $text['upgrade_warning']; ?>
                <form method="post" action="data/ajx/upload.php" class="upgrade-form">
                    <button type="submit" class="btn btn-upgrade"><i class="fa icon-angle-double-up"></i> <span><?php echo $text['upgrade']; ?></span></button>
                    <input type="hidden" name="ucmd" value="upgrade" />
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body cce-tab-panel">
                    <p class="cce-descr"><?php echo $text['cce_descr']; ?></p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 text-left">
                            <p class="cce-descr">
                                <?php echo $text['sources']; ?>:
                                <ul>
                                    <li><a href="https://bitbucket.org/kylon/cloud-clover-editor-cce" target="_blank">CCE</a></li>
                                    <li><a href="https://bitbucket.org/kylon/cfpropertylist-cce" target="_blank">CFPropertyList-CCE</a></li>
                                    <li><a href="https://bitbucket.org/kylon/smbios-list" target="_blank">SMBIOS-list</a></li>
                                </ul>
                                <span class="see-readme"><?php echo $text['see_readme']; ?></span>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 text-right">
                            <p id="usefull-links" class="cce-descr">
                                <?php echo $text['usefull_links']; ?>:
                                <ul id="usefull-links-ul" class="rtl">
                                    <li><a href="https://clover-wiki.zetam.org/Home" target="_blank">Clover Wiki</a></li>
                                    <li><a href="https://sourceforge.net/projects/cloverefiboot/" target="_blank">Clover EFI Sourceforge</a></li>
                                    <li><a href="https://cloud-acpi-editor.sourceforge.io/acpied/index.php" target="_blank">Cloud ACPI Editor</a></li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    <div class="text-center cce-btn-act">
                        <form id="saveForm" method="post" action="data/ajx/save.php"><input type="hidden" name="idx" value="" /></form>
                        <form id="createForm" method="post" action="data/ajx/upload.php"><input type="hidden" name="ucmd" value="ncfg" /></form>
                        <form id="open-cfg" method="post" action="data/ajx/upload.php" enctype="multipart/form-data">
                            <input id="cce-ocfg" name="config" type="file" accept=".plist" value="" />
                            <input type="hidden" name="ucmd" value="ocfg" />
                        </form>
                        <!-- Duplicate [index.php] -->
                        <form id="cceBankForm" name="nfscfg" method="post" action="data/ajx/upload.php">
                            <input type="hidden" name="ucmd" value="nfscfg" />
                            <input type="hidden" name="bid" value="" />
                        </form>

                        <div class="btn-group" role="group" aria-label="First group">
                            <button data-idx="<?php echo $_SESSION['cur_idx']; ?>" data-click="download-btn" data-toggle="modal" data-target="#saveas" class="btn download-btn" type="button"><i class="middle-sm fa icon-download" aria-hidden="true"></i> <span class="hidden-xs"><?php echo $text['download']; ?></span> </button>
                            <button form="createForm" class="btn create-btn" type="submit"><i class="middle-sm fa icon-file" aria-hidden="true"></i> <span class="hidden-xs"><?php echo $text['new_config']; ?></span> </button>
                            <button data-toggle="modal" data-target="#opencfg" data-click="opencfg-btn" class="btn btn-open" type="button"><i class="middle-sm fa icon-folder-open" aria-hidden="true"></i> <span class="hidden-xs"><?php echo $text['open_cfg']; ?></span> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row margin-top-30">
        <div class="col-md-12 title text-left">
            <?php echo $text['cce_sett_t']; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body cce-tab-panel">
                    <div class="row">
                        <div class="col-md-12 text-left">
                            <p class="cce_sett_title"><?php echo $text['cce_sett_hb64']; ?></p>
                            <label class="radio-inline">
                                <input type="radio" class="cce-sett" data-sett="hb64" name="cce_sett_hb64" <?php echo $cceSettHexChecked; ?> value="hex"> Hex
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="cce-sett" data-sett="hb64" name="cce_sett_hb64" <?php echo $cceSettHb64Checked; ?> value="b64"> Base64
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-left margin-top-30">
                            <p class="cce_sett_title"><?php echo $text['cce_sett_mode']; ?></p>
                            <label class="radio-inline">
                                <input type="radio" class="cce-sett" data-sett="mode"
                                       name="cce_sett_mode" <?php echo !isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) || $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'cce' ? 'checked':''; ?> value="cce"> <?php echo $text['cce_sett_mode_cce']; ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="cce-sett" data-sett="mode"
                                       name="cce_sett_mode" <?php echo isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) && $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode'] == 'oz' ? 'checked':''; ?> value="oz"> <?php echo $text['cce_sett_mode_oz']; ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }