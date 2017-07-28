<?php
/*
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * License - http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once 'data/utils.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo head(); ?>

        <link rel="stylesheet" href="<?php echo $link['bootstrap']; ?>">
        <link rel="stylesheet" href="<?php echo $link['icomoon']; ?>">
        <link rel="stylesheet" href="<?php echo $link['cce']; ?>">
    </head>
    <body id="cceindx">
        <div id="wrap">
            <div class="container pad-bottom-30">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="../style/img/cce-logo.png" class="img-responsive ccelogo" alt="CCE logo" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center cce-load-cfg">
                        <form method="post" action="data/ajx/upload.php" enctype="multipart/form-data">
                            <label for="file-upload" class="custom-file-upload"><span class="centered-tx"><?php echo $text['select_config']; ?></span></label>
                            <input id="file-upload" name="config" type="file" accept=".plist" value="" />
                            <input type="hidden" name="ucmd" value="ocfg" />
                            <input type="hidden" name="resetse" value="true" />
                        </form>
                    </div>

                    <!-- CCE Bank div -->
                    <div class="col-md-12 hidden-el cce-bank">
                        <form id="cceBankForm" name="nfscfg" method="post" action="data/ajx/upload.php">
                            <input type="hidden" name="ucmd" value="nfscfg" />
                            <input type="hidden" name="bid" value="" />
                            <input type="hidden" name="resetse" value="true" />
                        </form>

                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-sm-push-8 col-md-3 col-md-push-9">
                                <input style="color:white!important;" type="search" placeholder="<?php echo $text['search']; ?>" class="cce-bank-search" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cce-bank-container">
                                    <div class="row">
                                        <!-- CCE Bank container (js) -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <form class="index-new-form" method="post" action="data/ajx/upload.php">
                            <button class="btn index-new-btn" type="submit"><?php echo $text['create_new_cfg']; ?></button>
                            <input type="hidden" name="ucmd" value="ncfg" />
                            <input type="hidden" name="resetse" value="true" />
                        </form>
                        <div class="index-new-server-form">
                            <button class="btn index-new-server-btn cce-bank-btn">
                                <span class="cce-bank-t"><?php echo $text['load_from_server_cfg']; ?></span>
                                <span class="cce-open-t hidden-el"><?php echo $text['select_config']; ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="cce-tab-footer index-footer-overlay"><?php echo $text['credit']; ?></footer>
        </div>

        <script src="<?php echo $link['jquery']; ?>"></script>
        <script src="<?php echo $link['bootstrapjs']; ?>"></script>
        <script src="<?php echo $link['indexjs']; ?>"></script>
    </body>
</html>