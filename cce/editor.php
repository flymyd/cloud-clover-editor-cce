<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

session_start();

if (!isset($_SESSION['clover'])) {
    header('Location: index.php');
    exit();
}

require_once 'data/utils.php';
require_once 'data/components/CloudCloverEditor/tab_linker.php';

$each=0;
$cceMode = isset($_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']) ? $_SESSION['cce-sett'][$_SESSION['cur_idx']]['mode']:'cce';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo head(); ?>

        <link rel="stylesheet" href="<?php echo $link['bootstrap']; ?>">
        <link rel="stylesheet" href="<?php echo $link['simplesidebar']; ?>">
        <link rel="stylesheet" href="<?php echo $link['icomoon']; ?>">
        <link rel="stylesheet" href="<?php echo $link['psscrollbar']; ?>">
        <link rel="stylesheet" href="<?php echo $link['cce']; ?>">
    </head>
    <body class="bg-lightgrey">
        <div id="wrapper">
            <div id="sidebar-wrapper" class="scrollable">
                <ul class="sidebar-nav" role="tablist">
                    <?php foreach ($menuEntry as $key => $tx) {
                    $tab = strtolower(str_replace(' ','', $tx));

                    $hidden = ($cceMode == 'oz' &&
                        ($tab == "cloudclovereditor" || $tab == 'rtvariables' || $tab == 'smbios' || $tab == 'ozmosis') ) ||
                        ($cceMode == 'cce' && $tab != 'ozmosis') ? '':' hidden-el';

                    $nooz = $tab == "cloudclovereditor" || $tab == 'rtvariables' || $tab == 'smbios' || $tab == 'ozmosis' ? '':'nooz';
                    $active = $each == 0 ? 'active':'';
                    ?>
                    <li role="presentation" class="<?php echo $active.$nooz.$hidden; ?>">
                        <a href="#<?php echo $tab; ?>" aria-controls="<?php echo $tab; ?>" role="tab" data-toggle="tab"><i class="fa icon-<?php echo $key; ?>" aria-hidden="true"></i> <span><?php echo $tx; ?></span></a>
                    </li>
                    <?php $each=1; } $each=0; ?>
                </ul>
            </div>

            <div class="sidebar-mobile visible-xs">
                <div class="sidebar-mobile-cont"><i class="fa icon-angle-right vertical-center"></i> </div>
            </div>

            <div id="sidebar-right">
                <div class="side-right-btn">
                    <i class="fa icon-clone multi-conf-handle" aria-hidden="true"></i>
                </div>

                <div class="config-square-add" data-click="config-add">
                    <i class="fa icon-plus"></i>
                </div>

                <div class="side-right-cont scrollable">

                    <!-- Current config, must be first -->
                    <div class="config-square config-current" data-ccemode="<?php echo $cceMode; ?>" data-idx="<?php echo $_SESSION['cur_idx']; ?>" data-click="config-square">
                        <p class="config-filename"><?php echo $_SESSION['cur_idx']; ?></p>
                        <button data-toggle="modal" data-target="#saveas" class="save-config-square-btn" data-click="config-square-save" type="button">
                            <i class="fa icon-download" aria-hidden="true"></i> Download
                        </button>
                    </div>

                    <?php foreach ($_SESSION['config-list'] as $k => $v) {
                        if ($k == $_SESSION['cur_idx'])
                            continue;

                        $plistMode = isset($_SESSION['cce-sett'][$k]['mode']) ? $_SESSION['cce-sett'][$k]['mode']:'cce';
                        ?>
                    <div class="config-square" data-ccemode="<?php echo $plistMode; ?>" data-idx="<?php echo $k; ?>" data-click="config-square">
                        <form class="session-switch-form" method="post" action="data/ajx/upload.php">
                            <input type="hidden" name="ucmd" value="switchcfg" />
                            <input type="hidden" name="idx" value="<?php echo $k; ?>" />
                        </form>
                        <div class="config-close-x-btn" data-click="config-close">
                            <i class="fa icon-times x-config-sqaure" aria-hidden="true"></i>
                        </div>
                        <p class="config-filename"><?php echo $k; ?></p>
                        <button data-toggle="modal" data-target="#saveas" class="save-config-square-btn" data-click="config-square-save" type="button">
                            <i class="fa icon-download" aria-hidden="true"></i> Download
                        </button>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="tab-content main-tab">
                <?php foreach ($menuEntry as $tx) {
                    $tab = strtolower(str_replace(' ','',$tx)); ?>
                    <div role="tabpanel" class="tab-pane<?php echo $each==0?' active':''; ?>" id="<?php echo $tab; ?>">
                        <div class="container-fluid">
                            <?php echo drawTab($tab); ?>
                        </div>
                    </div>
                <?php $each=1; } $each=0; ?>
            </div>

            <footer class="cce-tab-footer"><?php echo $text['credit']; ?></footer>

            <!-- SAVE AS MODAL -->
            <div id="saveas" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header saveas-modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?php echo $text['save_file_as']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" form="saveForm" name="filename" value="" placeholder="cce-config" class="saveas-text cfg-name" />
                                        <label class="saveas-text-err hidden-el"><?php echo $text['invalid_conf_name']; ?></label>
                                    </div>

                                    <div class="form-group text-left">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="save-to-bank" /> <?php echo $text['save_to_bank']; ?>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group hidden-el bank-edit-key-ins">
                                        <input type="text" class="saveas-text" placeholder="Edit key">
                                    </div>

                                    <div class="form-group text-left hidden-el bank-config-edit-mode">
                                        <label class="bank-config-edit-mode-t text-center"><?php echo $text['bank_edit_mode']; ?></label>
                                        <label class="radio-inline">
                                            <input type="radio" name="configBankEditMode" id="bankEditModePub" value="public" checked> <?php echo $text['public']; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="configBankEditMode" id="bankEditModePriv" value="private"> <?php echo $text['private']; ?>
                                        </label>
                                    </div>

                                    <div class="text-center hidden-el bank-edit-key">
                                        <label><?php echo $text['bank_edit_key']; ?></label>
                                        <span class="gen-edit-key"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer saveas-modal-footer">
                            <button type="button" class="btn save-btn" data-click="save-modal"><?php echo $text['save']; ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COPY TO MODAL -->
            <div id="copyto" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header saveas-modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><?php echo $text['copy_to']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><?php echo $text['sel_config']; ?></label>
                                <select class="form-control copytoconfig-list" name="copytoconfig">
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer saveas-modal-footer">
                            <button type="button" class="btn copyto-btn" data-click="copyto-modal" data-source="">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OPEN MODAL -->
        <div id="opencfg" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header saveas-modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?php echo $text['open_cfg']; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <!-- form in cce.php [TODO: html5 d&d]-->
                                <div class="open-cfg-drop">
                                    <label class="open-cfg-drop-icon" for="cce-ocfg"><i class="fa icon-folder-open"></i> <label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="search" class="bank-search-editor" value="" placeholder="<?php echo $text['search']; ?>" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="cce-bank-container-editor">
                                            <div class="row">
                                                <!-- CCE Bank container (js) -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script src="<?php echo $link['jquery']; ?>"></script>
        <script src="<?php echo $link['bootstrapjs']; ?>"></script>
        <script src="<?php echo $link['jqueryui']; ?>"></script>
        <script src="<?php echo $link['jqueryuipunch']; ?>"></script>
        <script src="<?php echo $link['jquerymobileswipe']; ?>"></script>
        <script src="<?php echo $link['jcombobox']; ?>"></script>
        <script src="<?php echo $link['psscrollbarjs']; ?>"></script>
        <script src="<?php echo $link['editorjs']; ?>"></script>
    </body>
</html>