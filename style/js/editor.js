/*!
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * Licensed under GPLv3 license
 */
'use strict';

$(function() {
    var smbiosList = {};

    $(document).on('change', '#cce-ocfg', function () {
        $(this).parent().submit();
    });

    $.get('../../cce/data/res/smbios-list.xml', function (mac) { smbiosList = mac; });
    $('.scrollable').perfectScrollbar({wheel:0.5});
    $('.cce-combo').combobox();
    $('.panel-centry').sortable({
        items: '.single-centry',
        handle: '.centry-sort-handle',
        cursor: "move",
        containment: "parent",
        tolerance: "pointer",
        axis: 'x'
    });
    $('.ssdtOr .table-cont').sortable({
        items: 'tr',
        handle: '.ssdt-sortable',
        axis: 'y',
        containment: "parent",
        tolerance: "pointer",
        cursor: "move",
        start: function( event, ui ) {
            ui.item.parent().find('.ssdt-sortable').addClass('right');
            ui.item.parent().find('.inline-text').addClass('left');
            ui.item.parent().attr('data-sid','ssdtOr');
        },
        stop: function( event, ui ) {
            ui.item.parent().find('.ssdt-sortable').removeClass('right');
            ui.item.parent().find('.inline-text').removeClass('left');
            ui.item.parent().removeAttr('data-sid');
        }
    });

    /*
        Open from CCE Bank
     */
    $(document).on('click', '.bank-config-box', function () {
        $('#cceBankForm').find('input[name="bid"]').val($(this).attr('data-idx'));
        $('#cceBankForm').submit();
    });

    $(document).on('keyup', '.bank-search-editor', function () {
        var filt = $(this).val() == '' ? 'listall':$(this).val();

        drawConfigs(filt);
    });

    /*
        Save config
     */
    $(document).on('keyup','.saveas-text',function (e) {
        if (e.which === 13) $('#saveas').modal('hide');
    });

    $(document).on('change', '.save-to-bank', function () {
        var checked = $(this).prop('checked');

        if (checked) {
            var json = [$('#saveForm').find('input[name="idx"]').val(), 'plc'];
            var ret = ajax('chksvtobnk', json, false, false);

            if (!ret) { // Not found in CCE Bank
                $('.bank-config-edit-mode').removeClass('hidden-el');

                if ($('.saveas-text').val() == '') {
                    $('.saveas-text-err').removeClass('hidden-el');
                    $('.saveas-text').addClass('saveas-text-inp-err');
                }

                $('.saveas-text').bind('keyup', function () {
                    ret = 1;

                    if ($(this).val() != '') {
                        json = [$(this).val(), 'plc'];
                        ret = ajax('chkbnkname', json, false, false);
                    }

                    if (ret) {
                        $('.saveas-text-err').removeClass('hidden-el');
                        $('.saveas-text').addClass('saveas-text-inp-err');
                    } else {
                        $('.saveas-text-err').addClass('hidden-el');
                        $('.saveas-text').removeClass('saveas-text-inp-err');
                    }
                });
            } else {
                var editMode = ajax('getbnkmd', json, false, false);
                var confName = $('.download-btn').attr('data-idx');

                $('.cfg-name').val(confName.substr(0, confName.length-7));
                $('.cfg-name').prop('disabled', true);
                $('.save-to-bank').addClass('updmode');

                if (editMode == 'private')
                    $('.bank-edit-key-ins').val('').removeClass('hidden-el');
            }
        } else {
            resetSaveToBankInputs();
        }
    });

    $(document).on('change', 'input[name="configBankEditMode"]', function () {
        var type = $(this).val();

        if (type == 'private') {
            var editKey = ajax('genbnkkey', ['plc', 'plc'], false, false);

            $('.bank-edit-key').removeClass('hidden-el');
            $('.gen-edit-key').html(editKey);
        } else {
            $('.bank-edit-key').addClass('hidden-el');
            $('.gen-edit-key').html('');
        }
    });

    /*
        CCE Settings
     */
    $(document).on('change','.cce-sett',function () {
        var type = $(this).attr('data-sett');

        switch (type) {
            case 'hb64':
                var convType = $(this).val();
                var json = [type, convType];

                $('[data-field="Find"], [data-field="Replace"], [data-field="Custom"],' +
                        '.addProp td[data-field="Value"]').each(function () {
                    var val = $(this).text();

                    if (convType === 'hex') {
                        if (!isHex(val))
                            $(this).text(base64ToHEX(val));
                    } else {
                        if (isHex(val))
                            $(this).text(hexToBase64(val));
                    }
                });

                ajax('ccesett', json, false);
                break;
            case 'mode':
                var val = $(this).val();
                var json = [type, val];

                ajax('ccesett',json,false);
                ozmosize(val);
                break;
            default:
                break;
        }
    });

    /*
        Sidebars
     */
    $(document).on('click swipeleft swiperight','.sidebar-mobile',function (e) {
        e.stopPropagation();

        var sidebarCmd = $('#wrapper').hasClass('toggled') ? 'close':'open';

        sidebar(sidebarCmd);

        if ($('#sidebar-right').hasClass('sidebar-right-toggled'))
            sidebarRight('close');
    });

    $(document).on('click','.side-right-btn',function (e) {
        e.stopPropagation();

        var rSidebarCmd = $('#sidebar-right').hasClass('sidebar-right-toggled') ? 'close':'open';

        sidebarRight(rSidebarCmd);

        if ($('#wrapper').hasClass('toggled'))
            sidebar('close');
    });

    /*
        Generic
     */
    $(document).on('click', 'html', function (e) {
        if (currentInline != null && e.target.className != 'inline-input')
            inlineEdit(null,null);

        if ($('#wrapper').hasClass('toggled') && e.target.className !== 'sidebar-mobile-cont')
            sidebar('close');

        if ($('#sidebar-right').hasClass('sidebar-right-toggled'))
            sidebarRight('close');

        if ($('.entry-selected').length) {
            $('.entry-selected').each(function () {
                $(this).removeClass('entry-selected');
            });
        }
    });

    /*
        Inline edit
     */
    $(document).on('dblclick taphold keydown','.inline-text',function (e) {
        if (e.target.className != 'inline-input') inlineEdit($(this),'enbl');
        if (e.which == 13) inlineEdit(null, null);
    });

    /*
        CCE inputs
     */
    $(document).on('keyup paste','.cce-text',function (e) {
        if (isValidKeyup(e.which)) {
            var notf = e.notf;
            var act = $(this).val() !== '' ? 'setval':'unset';
            var path = $(this).attr('data-path');
            var json = [path, $(this).attr('data-field'), $(this).val()];

            if ($(this).hasClass('smbios-sn')) {

                $('.smbios-final-serial').html($(this).val());
                $('.smbios-unitN').val('');
                $('.smbios-weekN').val('');

            } else if ($(this).hasClass('single-item-title-upd')) {
                path = path.split('/');

                var subid = path[path.length - 1];
                var value = $(this).val();
                var titleList = value.length > 12 ? value.substr(0,12)+'...':value;

                if ($(this).hasClass('centry-title')) {

                    $('.modal-centry-title[data-subid="' + subid + '"]').html(value);
                    $('.centry-list-title[data-subid="' + subid + '"]').html(titleList);

                } else if ($(this).hasClass('kextP-name')) {

                    $('.modal-kextP-title[data-subid="'+subid+'"]').html(value);
                    $('.kextP-list-title[data-subid="'+subid+'"]').html(titleList);

                } else if ($(this).hasClass('kernelP-name')) {

                    $('.modal-kernelP-title[data-subid="'+subid+'"]').html(value);
                    $('.kernelP-list-title[data-subid="'+subid+'"]').html(titleList);

                }

            } else if ($(this).hasClass('smbios-boardsn') && $(this).hasClass('require-minlen')) { // Ozmosis SMBIOS board serial
                var obj = $(this).parent().find('label');

                if ($(this).parent().find('input').val().length < 17)
                    obj.find('span').removeClass('hidden-el');
                else
                    obj.find('span').addClass('hidden-el');

            } else if ($(this).hasClass('oz-mac')) { // Ozmosis MAC
                var r = /([a-f0-9]{2})([a-f0-9]{2})/i;
                var str = $(this).val().replace(/[^a-f0-9]/ig, "");

                while (r.test(str))
                    str = str.replace(r, '$1' + ':' + '$2');

                $(this).val(str.slice(0, 17));
                json[2] = $(this).val();

                if ($(this).val().length != 17 && $(this).val() != '')
                    return false;

            } else if ($(this).hasClass('oz-templates-tx') && $('.oz-disk-type').val() == '') { // Ozmosis Template text box
                return;
            }

            ajax(act,json,notf);
        }
    });

    $(document).on('change','.cce-checkbox', function () {
        var checked = $(this).is(':checked') ? true:false;
        var path = $(this).parent().parent().attr('data-path');
        var json = [path, $(this).attr('data-field'), checked];
        var act = !checked ? 'unset':'setval';

        if ($(this).hasClass('drop_dsm')) {
            $('.single_dsm').find('.cce-checkbox').each(function () {
                if (checked) {
                    $(this).prop('checked',false);
                    $(this).prop('disabled',true);
                    $(this).parent().addClass('color-disabled');
                } else {
                    $(this).prop('disabled',false);
                    $(this).parent().removeClass('color-disabled');
                }
            });

        } else if ($(this).hasClass('dsdtfix-c')) {
            var atLeastOneChecked = false;
            var fixmask = $('#manual_fixmask');

            $('#dsdt-fixes').find('.cce-checkbox').each(function () {
                if ($(this).is(':checked')) {
                    atLeastOneChecked = true;

                    if (!fixmask.prop('disabled')) {
                        var json2 = [fixmask.attr('data-path'), fixmask.attr('data-field'), ''];

                        ajax('unset',json2,false);
                        fixmask.val('');
                        fixmask.prop('disabled',true);
                    }
                    return false;
                }
            });

            if (!atLeastOneChecked && fixmask.prop('disabled'))
                fixmask.prop('disabled',false);

        } else if ($(this).hasClass('ssdt-gen')) {
            var i=0;

            $('.single_ssdt').find('.cce-checkbox').each(function () {

                if (i===2)
                    return false;

                if (checked) {
                    $(this).prop('checked',false);
                    $(this).prop('disabled',true);
                    $(this).parent().addClass('color-disabled');
                } else {
                    $(this).prop('disabled',false);
                    $(this).parent().removeClass('color-disabled');
                }

                ++i;
            });

        } else if ($(this).hasClass('inject_gfx')) {

            $('.single_inj').find('.cce-checkbox').each(function () {

                if (checked) {
                    $(this).prop('checked',false);
                    $(this).prop('disabled',true);
                    $(this).parent().addClass('color-disabled');
                } else {
                    $(this).prop('disabled',false);
                    $(this).parent().removeClass('color-disabled');
                }
            });

        } else if ($(this).attr('data-field') === 'FullTitle') {
            var ftitleO = $(this).hasClass('centry-ftitle') ? $(this).parent().parent().find('.ftitle'):$('.centry-title[data-subid="'+$(this).attr('data-subid')+'"]');
            var ftitle = $(this).hasClass('centry-ftitle') ? ftitleO.text():ftitleO.val();
            var titleType = checked ? 'Title':'FullTitle';
            var settitleType = checked ? 'FullTitle':'Title';
            var json2 = [path,titleType,ftitle];

            act = 'setval';
            json[1] = settitleType;
            json[2] = ftitle;
            ftitleO.attr('data-field',settitleType);

            ajax('unset',json2,false);

        } else if ($(this).hasClass('sip-flag-val')) {
            var csrInput = $('.csrActiveConf');
            var fflag = '0x'+genSIPBTRFlag('sip');

            if (fflag == '0x0')
                fflag = '';

            $('.fflag').text(fflag);
            csrInput.val(fflag);

            act = fflag == '' ? 'unset':'setval';
            json = [csrInput.attr('data-path'),csrInput.attr('data-field'),csrInput.val()];

        } else if ($(this).hasClass('btr-flag-val')) {
            var btrInput = $('input[data-field="BooterConfig"]');
            var fbflag = '0x'+genSIPBTRFlag('btr');

            if (fbflag == '0x0')
                fbflag = '';

            $('.fbflag').text(fbflag);
            btrInput.val(fbflag);

            act = fbflag == '' ? 'unset':'setval';
            json = [btrInput.attr('data-path'),btrInput.attr('data-field'),btrInput.val()];

        } else if ($(this).attr('data-field') === 'HWPEnable') {

            if (checked) {
                $('.hwpval').prop('disabled',false);
            } else {
                var hwpval = $('.hwpval');
                var json2 = [hwpval.attr('data-path'),hwpval.attr('data-field')];
                var act2 = 'unset';

                ajax(act2,json2,false);
                hwpval.val('');
                hwpval.prop('disabled',true);
            }

        } else if ($(this).attr('data-field') === 'DisableIntelInjection') { // Ozmosis

            $('.ozintel-inj').each(function () {
                $(this).prop('disabled', checked);
            });

        } else if ($(this).hasClass('ozal-flag-val')) { // Ozmosis - Acpi loader mode
            var ozalInput = $('input[data-field="AcpiLoaderMode"]');
            var curValue;
            var checkValue = '0x'+$(this).val();

            if (checkValue == '0x0') {
                $('.ozal-flag-val').each(function () {
                    if ($(this).val() != 0) {
                        if (checked) {
                            $(this).prop('checked', false);
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('checked', false);
                            $(this).prop('disabled', false);
                        }
                    }
                });

                curValue = checked ? '0x0':null;
            } else if (checkValue == '0x1') {
                if (!checked) {
                    $('.ozal-flag-val').each(function () {
                        if ($(this).val() != 0 && $(this).val() != 1) {
                            if (!checked)
                                $(this).prop('checked', false);
                        }
                    });
                }

                curValue = checked ? '0x1':null;
            } else {
                if (checked && !$('.ozal-flag-val[value="1"]').is(':checked'))
                    $('.ozal-flag-val[value="1"]').prop('checked', true);

                curValue = '0x'+genAcpiLoaderFlag();
            }

            $('.ozalflag').text(curValue);
            ozalInput.val(curValue);

            act = curValue == null ? 'unset':'setval';
            json = [ozalInput.attr('data-path'),ozalInput.attr('data-field'),ozalInput.val()];

        } else if ($(this).attr('data-field') === 'DisableAtiInjection') { // Ozmosis

            if (!checked) {
                $('.ozati-inj').prop('disabled',false);
            } else {
                var atiInjTx = $('.ozati-inj');
                atiInjTx.prop('disabled',true);
            }

        } else if ($(this).attr('data-field') === 'DisableVoodooHda') { // Ozmosis

            if (!checked) {
                $('.cce-checkbox[data-field="EnableVoodooHdaInternalSpdif"]').prop('disabled',false);
            } else {
                var checkb = $('.cce-checkbox[data-field="EnableVoodooHdaInternalSpdif"]');
                var json2 = [checkb.parent().parent().attr('data-path'),checkb.attr('data-field')];

                ajax('unset',json2,false);
                checkb.prop('checked',false);
                checkb.prop('disabled',true);
            }

        } else if ($(this).attr('data-field') === 'EnableVoodooHdaInternalSpdif') { // Ozmosis

            if (!checked) {
                $('.cce-checkbox[data-field="DisableVoodooHda"]').prop('disabled',false);
            } else {
                var checkb = $('.cce-checkbox[data-field="DisableVoodooHda"]');
                var json2 = [checkb.parent().parent().attr('data-path'),checkb.attr('data-field')];

                ajax('unset',json2,false);
                checkb.prop('checked',false);
                checkb.prop('disabled',true);
            }
        }

        ajax(act,json);
    });

    $(document).on('change', '.cce-sel', function (e) {
        var act = $(this).val() === '' ? 'unset':'setval';
        var json = [$(this).attr('data-path'), $(this).attr('data-field'), $(this).val()];
        var notf = e.notf;

        if ($(this).hasClass('dropTbSel')) {
            var types = ['TableId','Length'];

            for (var i=0, len=types.length; i<len; ++i) {
                var json2 = [$(this).attr('data-path'),types[i],''];
                ajax('unset',json2, false);
            }

            if ($(this).attr('data-field') === 'Signature') {
                if ($(this).find(':selected').text() === 'SSDT') {
                    var path = $(this).attr('data-path');
                    var opts = '';

                    for (var i=0, len=types.length; i < len; ++i) {
                        opts += '<option value="' + types[i] + '">' + types[i] + '</option>' + "\n";
                    }

                    $(this).parent().next().html(
                        '<select class="cce-sel select-inTable dropTbSel" data-path="' + path + '" data-field="SSDTkey">' +
                        '<option value=""></option>' +
                        opts +
                        '</select>'
                    );
                } else {
                    $(this).parent().next().html('');
                    $(this).parent().next().next().removeClass('inline-text');
                    $(this).parent().next().next().html('');
                }
            }

            if ($(this).attr('data-field') === 'SSDTkey') {
                var selected = $(this).find(':selected').text();

                $(this).parent().next().addClass('inline-text');
                $(this).parent().next().attr('data-field',selected);
                $(this).parent().next().html('placeholder');

                json[1] = selected;
                json[2] = 'placeholder';
            }

        } else if ($(this).hasClass('b-args')) {
            var b_argCont = $(this).hasClass('arg-oz') ? $('.barg-ozmosis'):$('.b-args-tx');
            json = null;

            if ($(this).val() === 'del') {
                act = 'unset';
                b_argCont.val('');
                json = [b_argCont.attr('data-path'),b_argCont.attr('data-field'),''];
            } else {
                var space = b_argCont.val() === '' ? '':' ';
                var current = b_argCont.val().split(' ');
                var exist = false;

                for (var ar=0, len=current.length; ar<len; ++ar) {
                    var valNewSub = $(this).val().substr(0,4);
                    var curValSub = current[ar].substr(0,4);

                    if ( (valNewSub === 'dark' && curValSub === 'dark') ||
                        (valNewSub === 'arch' && curValSub === 'arch') ||
                        (valNewSub === 'npci' && curValSub === 'npci') ) {
                        current.splice(ar,1);
                        break;
                    } else if (current[ar] === $(this).val()) {
                        exist = true;
                        notf = false;
                        break;
                    }
                }

                if (!exist) {
                    var args = current.join(' ')+space+$(this).val();

                    b_argCont.val(args);
                    json = [b_argCont.attr('data-path'),b_argCont.attr('data-field'),b_argCont.val()];
                }
            }

        } else if ($(this).hasClass('oz-disk-type')) { // Ozmosis Template - select disk type
            var ozTxBox = $('.oz-templates-tx');
            var content;

            json = [$(this).val(), 'plc'];
            content = $(this).val() == '' ? '' : ajax('getOzTemplData', json, false, false);

            ozTxBox.attr('data-field', $(this).val());
            ozTxBox.val(content);

            return;

        } else if ($(this).hasClass('oz-templ-type')) { // Ozmosis Template - select template type
            var b_argCont = $('.oz-templates-tx');
            var templVal = $(this).val();

            $(this).prop('selectedIndex', 0);

            if ($('.oz-disk-type').val() == '')
                return;

            if (templVal === 'del') {
                act = 'unset';
                b_argCont.val('');
                json = [b_argCont.attr('data-path'), b_argCont.attr('data-field'), ''];
            } else {
                var space = b_argCont.val() === '' ? '':' ';
                var current = b_argCont.val().split(' ');
                var args = current.join(' ') + space + templVal;

                b_argCont.val(args);
                json = [b_argCont.attr('data-path'), b_argCont.attr('data-field'), b_argCont.val()];
            }

        } else if ($(this).hasClass('enbl-scan')) {
            var disableAll = $(this).val() !== 'true' && $(this).val() !== 'false' ? false:true;

            $('.scanOp').each(function () {
                if ($(this).hasClass('cce-checkbox')) $(this).prop('checked',false);
                if ($(this).hasClass('cce-sel')) $(this).val('');

                $(this).prop('disabled',disableAll);

                if (disableAll) {
                    $(this).parent().parent().find('label').addClass('color-disabled');
                } else {
                    $(this).parent().parent().find('label').removeClass('color-disabled');
                }

            });

            if ($(this).val() === 'custom')
                act = 'unset';

        } else if ($(this).hasClass('matchoses')) {
            var textarea = $(this).parent().parent().parent().parent().find('.matchos-tx');
            json = null;

            if ($(this).val() === 'del') {
                act = 'unset';
                $(textarea).val('');
                json = [$(textarea).attr('data-path'),$(textarea).attr('data-field'),''];
            } else {
                var current = $(textarea).val().split(',');
                var exist = false;
                var selected = $(this).val().split('.');
                var osBaseSelected = selected[0]+'.'+selected[1];
                var toRemove = [];

                for (var ar=0, len=current.length; ar<len; ++ar) {
                    var currentLoop = current[ar].split('.');
                    var osBaseCurrent = currentLoop[0]+'.'+currentLoop[1];

                    if (selected[2] === 'x') {
                        if (osBaseCurrent === osBaseSelected && current[ar] !== $(this).val())
                            toRemove.push(ar);
                    }

                    if (current[ar] === $(this).val() || (osBaseCurrent === osBaseSelected && currentLoop[2] === 'x')) {
                        exist = true;
                        notf = false;
                        break;
                    }
                }

                while(toRemove.length)
                    current.splice(toRemove.pop(), 1);

                if (!exist) {
                    current.push($(this).val());

                    var args = current.filter(function(el){ return el !== "" }).join(',');

                    $(textarea).val(args);
                    json = [$(textarea).attr('data-path'),$(textarea).attr('data-field'),$(textarea).val()];
                }
            }

        } else if ($(this).hasClass('smbios-sel')) {
            act = json = null;

            switchSMBIOS($(this).val(), $(smbiosList));

        } else if ($(this).hasClass('smbios-yrsnsel') || $(this).hasClass('smbios-manlcsel')) {
            act = json = null;

            genMacSerial(false);
        }

        ajax(act,json,notf);
    });

    $(document).on('keyup change','.cce-numb', function (e) {
        if (e.type === 'change' || isValidNumbKey(e.which)) {
            var act = $(this).val() === '' ? 'unset':'setval';
            var json = [$(this).attr('data-path'),$(this).attr('data-field'),$(this).val()];

            ajax(act,json);
        }
    });

    $(document).on('combo-change keyup', '.combobox-opt, .cce-combo', function (e) {
        var act = null, json = null;

        if ($(this).hasClass('cce-combo')) {
            var data_field = $(this).attr('data-field');

            if (isValidKeyup(e.which) &&
                (data_field !== 'CsrActiveConfig' && data_field !== 'BooterConfig') ) {

                act = $(this).val() === '' ? 'unset':'setval';
                json = [$(this).attr('data-path'),data_field,$(this).val()];
            }
        } else {
            var ccecombo = $(this).parent().parent().find('.cce-combo');
            var data_field = ccecombo.attr('data-field');

            act = 'setval';
            json = [ccecombo.attr('data-path'),data_field,$(this).text()];

            if (data_field === 'CsrActiveConfig' || data_field === 'BooterConfig')
                updateGUIBits(data_field,$(this).text());

            if (data_field === 'AcpiLoaderMode')
                updateGUIBits(data_field, $(this).text());
        }

        ajax(act,json,e.notf);
    });

    $(document).on('click','.unit-shake,.week-shake', function () {
        var randomGen = '';

        if ($(this).hasClass('week-shake')) {
            randomGen = 1 + Math.floor(Math.random() * 52);
            var weekNumb = randomGen.toString().length == 1 ? '0'+randomGen.toString():randomGen;
            $(this).parent().parent().find('input').val(weekNumb);
        } else {
            randomGen = randomString(3).toUpperCase();
            $(this).parent().parent().find('input').val(randomGen);
        }

        genMacSerial(false);
    });

    $(document).on('sortupdate','.cce-sortable, .ssdtOr .table-cont',function (e, ui) {
        var oldKey=null, newKey=null, path=null;
        var sid = $(this).attr('data-sid');
        var i=0;

        switch (sid) {
            case 'panel-centry':
                var modalName = ui.item.attr('data-target');

                oldKey = ui.item.find('button').attr('data-index');
                path = ui.item.find('button').attr('data-path');

                if (ui.item.next().length) {
                    var next = ui.item.next();

                    if (!next.hasClass('single-centry'))
                        ui.item.detach().insertAfter(next);
                }

                $(modalName).detach().insertAfter(ui.item);

                $('.single-centry').each(function () {
                    var modalentry = $(this).next();
                    var path = $(this).attr('data-target').split('-');
                    path[path.length - 1] = i;
                    var nTdx = path.join('-');

                    $(this).attr('data-target',nTdx);
                    $(this).find('[data-subid]').attr('data-subid',i);
                    $(this).find('[data-id="cp-cEntry"], [data-id="del-cEntry"]').attr('data-index',i);

                    modalentry.find('[data-subid]').each(function () {
                        $(this).attr('data-subid',i);
                    });

                    modalentry.find('[data-path]').each(function () {
                        var path = $(this).attr('data-path').split('/');

                        if ($(this).hasClass('delpatch-btn'))
                            path[path.length - 2] = i;
                        else if ($(this).hasClass('subcen-tr'))
                            path[path.length - 3] = i;
                        else
                            path[path.length - 1] = i;

                        var nIdx = path.join('/');

                        $(this).attr('data-path',nIdx);
                    });

                    modalentry.attr('class','modal fade modalentry-'+i);
                    ++i;
                });

                newKey = ui.item.find('button').attr('data-index');
                break;
            case 'ssdtOr':
                oldKey = ui.item.attr('data-index');
                path = ui.item.attr('data-path');

                ui.item.parent().find('tr').each(function () {
                    $(this).attr('data-index',i);
                    $(this).find('.inline-text').attr('data-field',i);
                    ++i;
                });

                newKey = ui.item.attr('data-index');
                break;
            default:
                break;
        }

        ajax('sortval',[path,oldKey,newKey]);
    });

    $(document).on('click','.cpTbBtn, .copycEntry-btn',function(e) {
        e.stopImmediatePropagation();

        var btnCls = $(this).attr('data-id').substr(3);
        var type = $(this).hasClass('cpTbBtn') ? 'std':'centry';
        var selctdLen = type == 'std' ? $('.'+btnCls+' .table-cont tr.entry-selected').length:'centry';

        if ((type == 'std' && selctdLen) || (type == 'centry')) {
            var opts = '<option></option>';
            var fisrt = true;

            $('#sidebar-right').find('.config-square').find('.config-filename').each(function () {
                if (fisrt) {
                    fisrt = false;
                    return true;
                }

                if ($(this).parent().attr('data-ccemode') != 'oz')
                    opts += '<option value="'+$(this).text()+'">'+$(this).text()+'</option>';
            });

            if (type == 'centry')
                $(this).addClass('centry-selected');

            $('.copytoconfig-list').html(opts);
            $('.copyto-btn').attr('data-source',btnCls);
            $('#copyto').modal('show');
        }
    });

    // Do not loose selected entries
    $(document).on('click','.copytoconfig-list',function (e) {
        e.stopImmediatePropagation();
    });

    $(document).on('click','[data-click]',function (e) {
        switch ($(this).attr('data-click')) {
            case 'config-add':
                e.stopPropagation();

                var uid = randomString(6).toLowerCase();

                $('<div class="config-square" data-idx="cce-config-'+uid+'" data-click="config-square">' +
                    '<form class="session-switch-form" method="post" action="data/ajx/upload.php">' +
                    '<input type="hidden" name="ucmd" value="switchcfg" />' +
                    '<input type="hidden" name="idx" value="cce-config-'+uid+'" />' +
                    '</form>' +
                    '<div class="config-close-x-btn" data-click="config-close">' +
                    '<i class="fa icon-times x-config-sqaure" aria-hidden="true"></i>' +
                    '</div>' +
                    '<p class="config-filename">cce-config-'+uid+'</p>' +
                    '<button data-toggle="modal" data-target="#saveas" class="save-config-square-btn" data-click="config-square-save" type="button">' +
                    '<i class="fa icon-download" aria-hidden="true"></i> Download' +
                    '</button>' +
                    '</div>').insertAfter($('.config-square').last());

                ajax('sncfg',[uid,'plc'],false);

                $('.scrollable').perfectScrollbar('update');
                break;
            case 'config-close':
                e.stopPropagation();

                var idx = $(this).parent().attr('data-idx');

                $(this).parent().remove();
                $('.scrollable').perfectScrollbar('update');

                ajax('dscfg',[idx,'plc']);
                break;
            case 'config-square':
                e.stopPropagation();

                $(this).find('.session-switch-form').submit();
                break;
            case 'config-square-save':
                e.stopPropagation();

                $('#saveForm').find('input[name="idx"]').val($(this).parent().attr('data-idx'));
                break;
            case 'save-modal':
                $('#saveas').modal('hide');

                if ($('.save-to-bank').prop('checked')) {
                    var mode = $('.save-to-bank').hasClass('updmode') ? 'upd':'new';
                    var editKey = $('input[name="configBankEditMode"]:checked').val() == 'public' ? 'public':$('.gen-edit-key').text();
                    var confName = mode == 'new' ? $('.saveas-text').val():'plc';
                    var idx = $('#saveForm').find('input[name="idx"]').val();
                    var json = [editKey, confName, idx];
                    var ret;

                    if (mode == 'upd') {
                        var editKeyEl = $('.bank-edit-key-ins');

                        json[0] = editKeyEl.hasClass('hidden-el') ? 'k_public':'k_'+editKeyEl.find('.saveas-text').val();
                    }

                    ret = ajax('svtobnk', json, true, false);

                    if (ret && mode == 'new') {
                        var configBox = $('.config-square[data-idx="'+idx+'"]');

                        configBox.attr('data-idx', ret).find('.config-filename').text(ret);

                        if (configBox.hasClass('config-current'))
                            $('.download-btn').attr('data-idx', ret);
                        else
                            configBox.find('input[name="idx"]').val(ret);
                    }

                    resetSaveToBankInputs();
                } else {
                    $('#saveForm').submit();
                }

                $('#saveForm').find('input[name="idx"]').val('');
                $('#saveas').find('.saveas-text').val('');
                break;
            case 'download-btn':
                $('#saveForm').find('input[name="idx"]').val($(this).attr('data-idx'));
                break;
            case 'opencfg-btn':
                drawConfigs('listall');
                break;
            case 'copyto-modal':
                var source = $(this).attr('data-source');
                var json = null;
                var indexes = [$(this).parent().parent().find('.copytoconfig-list').val()];

                if (source !== 'cEntry' && source !== 'kernelP' && source !== 'kextP') {
                    $('.'+source+' .table-cont tr.entry-selected').each(function () {
                        indexes.push($(this).attr('data-index'));
                    });

                    json = [$('.'+source+' .table-cont tr.entry-selected:first').attr('data-dpath'), indexes];
                } else {
                    var obj = $('.centry-selected');

                    indexes.push(obj.attr('data-index'));
                    json = [obj.attr('data-path'),indexes];

                    obj.removeClass('centry-selected');
                }

                ajax('copy',json);

                $('#copyto').modal('hide');
                break;
            default:
                break;
        }
    });

    $(document).on('click tap','tr[data-path] td',function (e) {
        if (!$(e.target).hasClass('cce-checkbox') && !$(e.target).hasClass('cce-sel') &&
            !$(e.target).hasClass('inline-input')) {
            e.stopImmediatePropagation();
            e.preventDefault();
        }

        var tryMultiSelect = $(this).parent().parent().find('.entry-selected').length;

        if ( ((e.type != 'tap' && !e.ctrlKey) || tryMultiSelect == 0) || (e.type == 'tap' && tryMultiSelect == 0) ) {
            $('.entry-selected').each(function () {
                $(this).removeClass('entry-selected');
            });

            $(this).parent().addClass('entry-selected');
        } else {
            if ($(this).parent().hasClass('entry-selected')) {
                $(this).parent().removeClass('entry-selected');
            } else {
                $(this).parent().addClass('entry-selected');
            }
        }

        if (document.selection)
            document.selection.empty();
        else if (window.getSelection)
            window.getSelection().removeAllRanges();

        if ($(this).parent().hasClass('cpropm')) {
            tryMultiSelect = $(this).parent().parent().find('.entry-selected').length;

            if (tryMultiSelect <= 1) {
                var path = $(this).parent().attr('data-path')+'/CustomProperties';
                var qur = ajax('getcprops',[path,'plc'],false,false);
                var datas = qur ? qur:0;
                var tr = '';

                $(this).parent().parent().find('.entry-active').removeClass('entry-active');
                $(this).parent().addClass('entry-active');

                for (var i=0, len=datas.length; i<len; ++i) {
                    var key = datas[i]['Key'] ? datas[i]['Key']:'';
                    var val = datas[i]['Value'] ? datas[i]['Value']:'';

                    tr += '<tr data-path="'+path+'/'+i+'" data-dpath="'+path+'" data-index="'+i+'">' +
                        '<td class="inline-text" data-field="Key">'+key+'</td>' +
                        '<td class="inline-text" data-field="Value">'+val+'</td>' +
                        '</tr>';
                }

                $('.cPropS .table-cont').html(tr);
            }
        }
    });

    $(document).on('click','.delpatch-btn, .delTbBtn', function (e) {
        var btnCls = $(this).attr('data-id').substr(4);
        var json = null, tid = null, indexes = null;
        var i=0;

        if ($(this).hasClass('delpatch-btn')) {
            json = [$(this).attr('data-path'),$(this).attr('data-index')];
            tid = btnCls;
            $(this).parent().parent().remove();
        } else if ($('.entry-selected').parent().parent().hasClass(btnCls)) {
            var path='';

            indexes=[];
            tid = btnCls;

            $('.'+tid).find('.entry-selected').each(function () {
                if (path === '') path=$(this).attr('data-dpath');

                indexes.push($(this).attr('data-index'));

                $(this).remove();
            });

            json = [path, indexes];
        }

        switch (tid) {
            case 'dsdtP':
            case 'ssdtTb':
            case 'cPropM':
            case 'cPropS':
            case 'vbiosP':
            case 'mNvI':
            case 'mAI':
            case 'subCEn':
            case 'cToolE':
                if (tid === 'cPropM')
                    $('.cPropS .table-cont').html('');

                $('.'+tid+' tbody tr[data-path]').each(function () {
                    var path = $(this).attr('data-path').split('/');
                    path[path.length - 1] = i;
                    var nIdx = path.join('/');

                    $(this).attr('data-path',nIdx);
                    $(this).attr('data-index',i);
                    ++i;
                });
                break;
            case 'ssdtOr':
            case 'disaAml':
            case 'whiteB':
            case 'blackB':
            case 'forceKx':
            case 'hideVl':
                $('.'+tid+' tbody tr[data-path]').each(function () {
                    $(this).find('.inline-text').attr('data-field',i);
                    $(this).attr('data-index',i);
                    ++i;
                });
                break;
            case 'addProp':
            case 'cLegE':
            case 'SMslots':
            case 'SMram':
                $('.'+tid+' tbody tr[data-path]').each(function () {
                    var path = $(this).attr('data-path').split('/');
                    path[path.length - 1] = i;
                    var nIdx = path.join('/');

                    $(this).attr('data-path',nIdx);
                    $(this).find('.cce-sel').attr('data-path',nIdx);
                    $(this).attr('data-index',i);
                    ++i;
                });
                break;
            case 'disaDrv':
                $('.'+tid+' tbody tr[data-path]').each(function () {
                    $(this).find('.cce-sel').attr('data-field',i);
                    $(this).attr('data-index',i);
                    ++i;
                });
                break;
            case 'cEntry':
                // WAITING FOR A BETTER SOLUTION
                e.stopPropagation();

                var modalRem = $(this).parent().parent().attr('data-target');
                var modalNumb = $('.panel-centry').find('.modal').length;

                if (modalNumb > 1) {
                    $('.panel-centry div'+modalRem).remove();
                } else {
                    var elem = $('.panel-centry div'+modalRem);

                    elem.attr('class','modal fade modalentry--');
                    elem.find('h4[data-subid]').text('new');
                    elem.find('[data-path]').each(function () {
                        if ($(this).hasClass('cce-text')) {
                            $(this).text('');
                            $(this).val('');
                        }

                        if ($(this).hasClass('checkbox'))
                            $(this).find('.cce-checkbox').prop('checked',false);

                        if ($(this).hasClass('cce-sel'))
                            $(this).find(':selected').prop('selected',false);
                    });

                    elem.find('.subCEn .table-cont tr').each(function () {
                        $(this).remove();
                    });
                }

                $('.single-centry').each(function () {
                    var oldModalTarg = $(this).attr('data-target');
                    var modalentry = $(this).parent().find(oldModalTarg);
                    var path = $(this).attr('data-target').split('-');
                    path[path.length - 1] = i;
                    var nTdx = path.join('-');

                    $(this).attr('data-target',nTdx);
                    $(this).find('[data-subid]').attr('data-subid',i);
                    $(this).find('.delpatch-btn').attr('data-index',i);
                    modalentry.find('[data-subid]').each(function () {
                        $(this).attr('data-subid',i);

                    });
                    modalentry.find('[data-path]').each(function () {
                        var path = $(this).attr('data-path').split('/');

                        if ($(this).hasClass('delpatch-btn'))
                            path[path.length - 2] = i;
                        else if ($(this).hasClass('subcen-tr'))
                            path[path.length - 3] = i;
                        else
                            path[path.length - 1] = i;

                        var nIdx = path.join('/');

                        $(this).attr('data-path',nIdx);
                    });
                    modalentry.attr('class','modal fade modalentry-'+i);
                    ++i;
                });
                break;
            case 'kextP':
            case 'kernelP':
            case 'bootefiP':
                // WAITING FOR A BETTER SOLUTION
                e.stopImmediatePropagation();

                var modalRem = $(this).parent().parent().attr('data-target');
                var modalNumb = $('.panel-'+tid).find('.modal').length;

                if (modalNumb > 1) {
                    $('.panel-'+tid+' div'+modalRem).remove();
                } else {
                    var elem = $('.panel-'+tid+' div'+modalRem);

                    elem.attr('class','modal fade modal'+tid+'--');
                    elem.find('[data-subid]').text('');
                    elem.find('[data-path]').each(function () {
                        if ($(this).hasClass('cce-text')) {
                            $(this).text('');
                            $(this).val('');
                        }

                        if ($(this).hasClass('checkbox'))
                            $(this).find('.cce-checkbox').prop('checked',false);
                    });
                }

                $('.single-'+tid).each(function () {
                    var oldModalTarg = $(this).attr('data-target');
                    var modalkext = $(this).parent().find(oldModalTarg);
                    var path = $(this).attr('data-target').split('-');
                    path[path.length - 1] = i;
                    var nTdx = path.join('-');

                    $(this).attr('data-target',nTdx);
                    $(this).find('[data-subid]').attr('data-subid',i);
                    $(this).find('.delpatch-btn').attr('data-index',i);
                    modalkext.find('[data-subid]').each(function () {
                        $(this).attr('data-subid',i);
                    });
                    modalkext.find('[data-path]').each(function () {
                        var path = $(this).attr('data-path').split('/');

                        path[path.length - 1] = i;

                        var nIdx = path.join('/');

                        $(this).attr('data-path',nIdx);
                    });
                    modalkext.attr('class','modal fade modal'+tid+'-'+i);
                    ++i;
                });
                break;
            default:
                break;
        }

        ajax('unset', json);
    });

    $(document).on('click', '.addTbBtn', function () {
        var tid = $(this).attr('data-id').substr(4);
        var newIndex = $('.'+tid+' .table-cont tr').length;

        switch (tid) {
            case 'dsdtP':
                $('.'+tid+' .table-cont').append(
                    '<tr data-path="ACPI/DSDT/Patches/'+newIndex+'" data-dpath="ACPI/DSDT/Patches" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Comment">new</td>' +
                    '<td class="inline-text" data-field="Find">ff</td>' +
                    '<td class="inline-text" data-field="Replace">ff</td>' +
                    '<td><input class="cce-checkbox" data-field="Disabled" type="checkbox" /></td>' +
                    '</tr>');
                break;
            case 'ssdtTb':
                var signatures = ['APIC','BGRT','CSRT','DMAR','ECDT','FACP','FPDT','HPET','LPIT','MCFG','MSDM','SLIC','SSDT','TCPA','TPM2'];
                var opts = '';

                for (var i=0, len=signatures.length; i<len; ++i)
                    opts += '<option value="'+signatures[i]+'">'+signatures[i]+'</option>'+"\n";

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="ACPI/DropTables/'+newIndex+'" data-dpath="ACPI/DropTables" data-index="'+newIndex+'">' +
                    '<td>' +
                    '<select class="cce-sel select-inTable dropTbSel" data-path="ACPI/DropTables/'+newIndex+'" data-field="Signature">'+
                    '<option value=""></option>'+
                    opts+
                    '</select>'+
                    '</td>' +
                    '<td></td>' +
                    '<td data-field="SSDTkeyVal"></td>' +
                    '</tr>');
                break;
            case 'ssdtOr':
            case 'disaAml':
            case 'whiteB':
            case 'blackB':
            case 'forceKx':
            case 'hideVl':
                var dataPath='',sorting='';

                if (tid === 'ssdtOr') {
                    dataPath='ACPI/SortedOrder';
                    sorting='<td class="ssdt-sortable"><i class="fa icon-arrows-v"></i></td>';
                }
                if (tid === 'disaAml') dataPath='ACPI/DisabledAML';
                if (tid === 'whiteB') dataPath='Boot/WhiteList';
                if (tid === 'blackB') dataPath='Boot/BlackList';
                if (tid === 'forceKx') dataPath='KernelAndKextPatches/ForceKextsToLoad';
                if (tid === 'hideVl') dataPath='GUI/Hide';

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="'+dataPath+'" data-dpath="'+dataPath+'" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="'+newIndex+'">new</td>' +
                    sorting +
                    '</tr>');
                break;
            case 'addProp':
                var device = ['ATI','NVidia','IntelGFX','LAN','WIFI','Firewire','SATA','IDE','HDA','HDMI','LPC','SmBUS','USB'];
                var opts = '';

                for (var i=0, len=device.length; i<len; ++i)
                    opts += '<option value="'+device[i]+'">'+device[i]+'</option>'+"\n";

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="Devices/AddProperties/'+newIndex+'" data-dpath="Devices/AddProperties" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Device">' +
                    '<select class="cce-sel select-inTable" data-path="Devices/AddProperties/'+newIndex+'" data-field="Device">'+
                    '<option value=""></option>'+
                    opts+
                    '</select>'+
                    '</td>' +
                    '<td class="inline-text" data-field="Key">new</td>' +
                    '<td class="inline-text" data-field="Value">ff</td>' +
                    '</tr>');
                break;
            case 'cPropM':
                $('.'+tid+' .table-cont').append(
                    '<tr class="cpropm" data-path="Devices/Arbitrary/'+newIndex+'" data-dpath="Devices/Arbitrary" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Comment">new</td>' +
                    '<td class="inline-text" data-field="PciAddr">ff</td>' +
                    '</tr>');
                break;
            case 'cPropS':
                var cpropmI = $('.cPropM .table-cont').find('.entry-active').attr('data-path');

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="'+cpropmI+'/CustomProperties/'+newIndex+'" data-dpath="'+cpropmI+'/CustomProperties" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Key">new</td>' +
                    '<td class="inline-text" data-field="Value">new</td>' +
                    '</tr>');
                break;
            case 'disaDrv':
                var drivers = [
                    '',
                    '.:: 32 bit ::.',
                    'Ps2KeyboardDxe','Ps2MouseAbsolutePointerDxe',
                    '.:: 64 bit ::.',
                    'NvmExpressDxe',
                    '.:: 32 & 64 bit ::.',
                    'Ps2MouseDxe','UsbMouseDxe','VBoxExt2','VBoxExt4','XhciDxe',
                    '.:: UEFI ::.',
                    'CsmVideoDxe','DataHubDxe','EmuVariableUefi','OsxAptioFixDrv',
                    'OsxAptioFix2Drv','OsxLowMemFixDrv','PartitionDxe',
                    '.:: Common ::.',
                    'VBoxHfs'];
                var opts = '<option value=""></option>';

                for (var i=0, len=drivers.length; i<len; ++i) {
                    opts += drivers[i].substr(0,2) !== '.:' ?
                        '<option value="'+drivers[i]+'">'+drivers[i]+'</option>'+"\n" :
                        '<option value="" class="sel-sep" disabled>'+drivers[i]+'</option>'+"\n";
                }

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="placeholder" data-dpath="DisableDrivers" data-index="'+newIndex+'">' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="DisableDrivers" data-field="'+newIndex+'">'+
                    opts+
                    '</select>'+
                    '</td>' +
                    '</tr>');
                break;
            case 'customEntry':
                // WAITING FOR A BETTER SOLUTION
                var SPECIAL_newIndex = $('.panel-centry .single-centry').length;
                var classesNew = 'modal fade modalentry-'+SPECIAL_newIndex;
                var datapathNew = 'GUI/Custom/Entries/'+SPECIAL_newIndex;

                $('<div class="single-item single-centry" data-toggle="modal" data-target=".modalentry-'+SPECIAL_newIndex+'">' +
                    '<div class="centry-sort-handle"><i class="fa icon-hand-paper-o"></i></div>' +
                    '<div><i class="fa icon-drive single-item-icon"></i></div>' +
                    '<div class="single-item-title centry-list-title" data-subid="'+SPECIAL_newIndex+'">new</div>' +
                    '<div>' +
                    '<button data-id="cp-cEntry" class="single-item-cp-btn bg-yellow-flat left copycEntry-btn" data-path="GUI/Custom/Entries" data-index="'+SPECIAL_newIndex+'">' +
                    '<i class="fa icon-files-o"></i></button>' +
                    '<button data-id="del-cEntry" class="single-item-del-btn delpatch-btn left" data-path="GUI/Custom/Entries" data-index="'+SPECIAL_newIndex+'">' +
                    '<i class="fa icon-times"></i></button>' +
                    '</div>').insertAfter($('.panel-centry .modal').last());

                $('.panel-centry .modal').first().clone().insertAfter($('.panel-centry .single-centry').last());

                var modalNew = $('.panel-centry .modal').last();

                modalNew.attr('class',classesNew);
                modalNew.find('[data-subid]').attr('data-subid',SPECIAL_newIndex);
                modalNew.find('h4[data-subid]').text('new');
                modalNew.find('[data-path]').each(function () {
                    $(this).attr('data-path',datapathNew);

                    if ($(this).hasClass('cce-text')) {
                        $(this).text('');
                        $(this).val('');
                    }

                    if ($(this).hasClass('checkbox'))
                        $(this).find('.cce-checkbox').prop('checked',false);

                    if ($(this).hasClass('cce-sel'))
                        $(this).find(':selected').prop('selected',false);
                });

                modalNew.find('.subCEn .table-cont tr').each(function () {
                    $(this).remove();
                });

                ajax('setval',[datapathNew,'Title','new']);
                break;
            case 'subCEn':
                var rootTag = $(this).parent().parent().parent();
                var subCEId = rootTag.attr('data-subid');
                var SPECIAL_newIndex = rootTag.find('.table-scrollbody .'+tid+' tbody tr').length - 1;

                $('.'+tid+' .table-cont').append(
                    '<tr class="subcen-tr" data-path="GUI/Custom/Entries/'+subCEId+'/SubEntries/'+SPECIAL_newIndex+'" data-dpath="GUI/Custom/Entries/'+subCEId+'/SubEntries" data-index="'+SPECIAL_newIndex+'">' +
                    '<td class="inline-text ftitle" data-field="Title">new</td>' +
                    '<td class="inline-text" data-field="AddArguments">new</td>' +
                    '<td><input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" /></td>' +
                    '<td><input class="cce-checkbox" data-field="CommonSettings" type="checkbox" /></td>' +
                    '</tr>');
                break;
            case 'cLegE':
                var vType = ['Other', 'Windows', 'Linux'];
                var opts = '';

                for (var vt=0, len=vType.length; vt<len; ++vt)
                    opts += '<option value="'+vType[vt]+'">'+vType[vt]+'</option>';

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="GUI/Custom/Legacy/'+newIndex+'" data-dpath="GUI/Custom/Legacy" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Volume">new</td>' +
                    '<td class="inline-text ftitle" data-field="Title">new</td>' +
                    '<td class="inline-text" data-field="Hotkey">new</td>' +
                    '<td><input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" /></td>' +
                    '<td><input class="cce-checkbox" data-field="Hidden" type="checkbox" /></td>' +
                    '<td><input class="cce-checkbox" data-field="Disabled" type="checkbox" /></td>' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="GUI/Custom/Legacy/'+newIndex+'" data-field="Type">'+
                    opts+
                    '</select>'+
                    '</td>' +
                    '</tr>');
                break;
            case 'cToolE':
                $('.'+tid+' .table-cont').append(
                    '<tr data-path="GUI/Custom/Tool/'+newIndex+'" data-dpath="GUI/Custom/Tool" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Volume">new</td>' +
                    '<td class="inline-text" data-field="Path">new</td>' +
                    '<td class="inline-text ftitle" data-field="Title">new</td>' +
                    '<td class="inline-text" data-field="Arguments">new</td>' +
                    '<td class="inline-text" data-field="Hotkey">new</td>' +
                    '<td><input class="cce-checkbox centry-ftitle" data-field="FullTitle" type="checkbox" /></td>' +
                    '<td><input class="cce-checkbox" data-field="Hidden" type="checkbox" /></td>' +
                    '<td><input class="cce-checkbox" data-field="Disabled" type="checkbox" /></td>' +
                    '</tr>');
                break;
            case 'vbiosP':
                $('.'+tid+' .table-cont').append(
                    '<tr data-path="Graphics/PatchVBiosBytes/'+newIndex+'" data-dpath="Graphics/PatchVBiosBytes" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Find">ff</td>' +
                    '<td class="inline-text" data-field="Replace">ff</td>' +
                    '</tr>');
                break;
            case 'mNvI':
                $('.'+tid+' .table-cont').append(
                    '<tr data-path="Graphics/NVIDIA/'+newIndex+'" data-dpath="Graphics/NVIDIA" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Model">new</td>' +
                    '<td class="inline-text" data-field="IOPCIPrimaryMatch">ff</td>' +
                    '<td class="inline-text" data-field="IOPCISubDevId">ff</td>' +
                    '<td class="inline-text" data-field="VRAM">0</td>' +
                    '<td class="inline-text" data-field="VideoPorts">0</td>' +
                    '<td><input class="cce-checkbox" data-field="LoadVBios" type="checkbox" /></td>' +
                    '</tr>');
                break;
            case 'mAI':
                $('.'+tid+' .table-cont').append(
                    '<tr data-path="Graphics/ATI/'+newIndex+'" data-dpath="Graphics/ATI" data-index="'+newIndex+'">' +
                    '<td class="inline-text" data-field="Model">new</td>' +
                    '<td class="inline-text" data-field="IOPCIPrimaryMatch">ff</td>' +
                    '<td class="inline-text" data-field="IOPCISubDevId">ff</td>' +
                    '<td class="inline-text" data-field="VRAM">0</td>' +
                    '</tr>');
                break;
            case 'kextP':
            case 'kernelP':
            case 'bootefiP':
                // WAITING FOR A BETTER SOLUTION
                var patchType;
                var title;

                switch (tid) {
                    case 'kextP':
                        patchType = 'KextsToPatch';
                        title = 'new';
                        break;
                    case 'kernelP':
                        patchType = 'KernelToPatch';
                        title = 'new';
                        break;
                    case 'bootefiP':
                        patchType = 'BootPatches';
                        title = 'Boot Patch';
                        break;
                    default:
                        break;
                }

                var SPECIAL_newIndex = $('.panel-'+tid+' .single-'+tid).length;
                var classesNew = 'modal fade modal'+tid+'-'+SPECIAL_newIndex;
                var datapathNew = 'KernelAndKextPatches/'+patchType+'/'+SPECIAL_newIndex;

                $('<div class="single-item single-'+tid+'" data-toggle="modal" data-target=".modal'+tid+'-'+SPECIAL_newIndex+'">' +
                    '<div><i class="fa icon-gears single-item-icon"></i></div>' +
                    '<div class="single-item-title '+tid+'-list-title" data-subid="'+SPECIAL_newIndex+'">'+title+'</div>' +
                    '<div>' +
                    '<button data-id="cp-'+tid+'" class="single-item-cp-btn bg-yellow-flat left copycEntry-btn" data-path="KernelAndKextPatches/'+patchType+'" data-index="'+SPECIAL_newIndex+'">' +
                    '<i class="fa icon-files-o"></i></button>' +
                    '<button data-id="del-'+tid+'" class="single-item-del-btn delpatch-btn left" data-path="KernelAndKextPatches/'+patchType+'" data-index="'+SPECIAL_newIndex+'">' +
                    '<i class="fa icon-times"></i></button>' +
                    '</div>').insertAfter($('.panel-'+tid+' .modal').last());

                $('.panel-'+tid+' .modal').first().clone().insertAfter($('.panel-'+tid+' .single-'+tid).last());

                var modalNew = $('.panel-'+tid+' .modal').last();

                modalNew.attr('class',classesNew);
                modalNew.find('[data-subid]').attr('data-subid',SPECIAL_newIndex).text(title);
                modalNew.find('[data-path]').each(function () {
                    $(this).attr('data-path',datapathNew);

                    if ($(this).hasClass('cce-text')) {
                        $(this).text('');
                        $(this).val('');
                    }

                    if ($(this).hasClass('checkbox'))
                        $(this).find('.cce-checkbox').prop('checked',false);
                });
                break;
            case 'SMram':
                var freq = [200,266,333,366,400,433,533,667,800,1066,1333,1600,1800,2000,2133,2200,2400,2600];
                var type = ['DDR','DDR2','DDR3','DDR4'];
                var opts, opts2, opts3, opts4;

                opts = opts2 = opts3 = opts4 = '<option value=""></option>';

                for (var v=0; v<=23; ++v)
                    opts += '<option value="'+v+'">'+v+'</option>';

                for (var r=1024; r<16400; r*=2)
                    opts2 += '<option value="'+r+'">'+r+'</option>';

                for (var g=0, len=freq.length; g<len; ++g)
                    opts3 += '<option value="'+freq[g]+'">'+freq[g]+'</option>';

                for (var t=0, len=type.length; t<len; ++t)
                    opts4 += '<option value="'+type[t]+'">'+type[t]+'</option>';

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="SMBIOS/Memory/Modules/'+newIndex+'" data-dpath="SMBIOS/Memory/Modules" data-index="'+newIndex+'">' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/'+newIndex+'" data-field="Slot">'+opts+'</select>' +
                    '</td>' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/'+newIndex+'" data-field="Size">'+opts2+'</select>' +
                    '</td>' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/'+newIndex+'" data-field="Frequency">'+opts3+'</select>' +
                    '</td>' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Memory/Modules/'+newIndex+'" data-field="Type">'+opts4+'</select>' +
                    '</td>' +
                    '<td class="inline-text" data-field="Vendor">new</td>' +
                    '<td class="inline-text" data-field="Serial"></td>' +
                    '<td class="inline-text" data-field="Part"></td>' +
                    '</tr>');
                break;
            case 'SMslots':
                var device = ['ATI','NVidia','IntelGFX','LAN','WIFI','Firewire','HDMI','USB','NVME'];
                var type = {'PCI':0,'PCIe X1':1,'PCIe X2':2,'PCIe X4':4,'PCIe X8':8,'PCIe X16':16};
                var opts, opts2;

                opts = opts2 = '<option value=""></option>';

                for (var e=0, len=device.length; e<len; ++e)
                    opts += '<option value="'+device[e]+'">'+device[e]+'</option>';

                for (var n in type)
                    opts2 += '<option value="'+type[n]+'">'+n+'</option>';

                $('.'+tid+' .table-cont').append(
                    '<tr data-path="SMBIOS/Slots/'+newIndex+'" data-dpath="SMBIOS/Slots" data-index="'+newIndex+'">' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Slots/'+newIndex+'" data-field="Device">'+opts+'</select>' +
                    '</td>' +
                    '<td class="inline-text" data-field="ID"></td>' +
                    '<td class="inline-text" data-field="Name">new</td>' +
                    '<td>' +
                    '<select class="cce-sel select-inTable" data-path="SMBIOS/Slots/'+newIndex+'" data-field="Type">'+opts2+'</select>' +
                    '</td>' +
                    '</tr>');
                break;
            default:
                break;
        }
    });
});

///////////////////////
//
//    FUNCTIONS
//
//////////////////////
var currentInline=null;

function inlineEdit(obj, act) {
    var val = $(obj).text();

    if (currentInline != obj && $('.inline-input').length) {
        var elemParent = $('.inline-input').parent();
        var json = [elemParent.parent().attr('data-path'), elemParent.attr('data-field'), $('.inline-input').val()];

        elemParent.css('padding','');
        elemParent.html($('.inline-input').val().trim());
        ajax('setval',json);
    }

    if (act != null) {
        $(obj).css('padding','0px');
        $(obj).html('<input type="text" class="inline-input" value="'+val.trim()+'" />');
        $('.inline-input').focus();
    }

    currentInline = $(obj);
}

function ajax(type,vals,notf,async) {
    var result = null;

    if (notf === undefined) notf = true;
    if (async === undefined) async = true;

    if (type != null && vals != null) {
        $.ajax({
            method: "POST",
            dataType: "json",
            cache: false,
            async: async,
            url: 'data/ajx/write.php',
            data: {type:type, vals:vals},
            success: function (re) {
                if (notf) {
                    cceNotf('success','Saved!','check',1000);
                }
                result = re;
            },
            error: function () {
                cceNotf('error','Error!','times',2000);
            }
        });
    }

    return result;
}

function sidebar(action) {
    var icon = $('.sidebar-mobile').find('i');

    if (action === 'open') {
        $('#wrapper').addClass('toggled');
        $('.sidebar-mobile').addClass('mobile-toggled');
        icon.removeClass('icon-angle-right');
        icon.addClass('icon-angle-left');
    } else {
        $('#wrapper').removeClass('toggled');
        $('.sidebar-mobile').removeClass('mobile-toggled');
        icon.removeClass('icon-angle-left');
        icon.addClass('icon-angle-right');
    }
}

function sidebarRight(action) {
    if (action === 'open')
        $('#sidebar-right').addClass('sidebar-right-toggled');
    else
        $('#sidebar-right').removeClass('sidebar-right-toggled');
}

function cceNotf(type, message, icon, duration) {

    if ($('.cceNotf').length === 0)
        $('body').append('<div class="cceNotf"></div>');

    if (icon !== '')
        icon = "<i class='fa icon-"+icon+"'></i> ";

    var html = $('<div class="alert alert-'+type+'">'+icon+'<span>'+message+'</span></div>').fadeIn(500);

    $('.cceNotf').append(html);

    setTimeout(function() {
        $(html).fadeOut(500, function() {
            $(this).remove();
        });
    }, duration);
}

function isValidKeyup(key) {
    var valid=false;

    if ( (key <= 8 || key === 32) || ( (key >= 46 && key <= 90) || key > 145 ) )
        valid=true;

    return valid;
}

function isValidNumbKey(key) {
    var valid=false;

    if ( (key >= 48 && key <=57) || key === 8 || key === 13 || key === 46)
        valid=true;

    return valid;
}

/**
 * Update SMBIOS values on screen when a Mac SMBIOS is selected
 *
 * @param string prodName
 * @param mixed list
 */
function switchSMBIOS(prodName,list) {
    $(list).find('mac').each(function () {
        if ($(this).find('ProductName').text() == prodName) {
            var yrsn = $(this).find('Year').text().split(',');
            var opts = '';

            for (var m=0, len=yrsn.length; m<len; ++m)
                opts += '<option value="'+yrsn[m]+'">'+yrsn[m]+'</option>';

            $('.smbios-speccpu').html($(this).find('specs').find('cpu').text());
            $('.smbios-specram').html($(this).find('specs').find('ram').text());
            $('.smbios-specgfx').html($(this).find('specs').find('gfx').text());

            $('.smbios-pdname').val($(this).find('ProductName').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-family').val($(this).find('Family').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-breldate').val($(this).find('BiosReleaseDate').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-bver').val($(this).find('BiosVersion').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-bvend').val($(this).find('BiosVendor').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-boardid').val($(this).find('BoardID').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-boardver').val($(this).find('BoardVersion').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-boardtp').val($(this).find('BoardType').text()).trigger($.Event('change', {notf:false}));
            $('.smbios-boardman').val($(this).find('BoardManufacturer').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-chtp').val($(this).find('ChassisType').text()).trigger($.Event('change', {notf:false}));
            $('.smbios-chman').val($(this).find('ChassisManufacturer').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-chasstype').val($(this).find('ChassisAsset').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-locinchass').val($(this).find('LocationInChassis').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-ver').val($(this).find('Version').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-man').val($(this).find('Manufacturer').text()).trigger($.Event('keyup', {which:8,notf:false}));
            $('.smbios-yrsnsel').html(opts);
            $('.smbios-mdl-code').html($(this).find('ModelId').text());

            $(this).find('Mobile').text() === 'true' ? $('.cce-checkbox[data-field="Mobile"]').prop('checked', true):
                $('.cce-checkbox[data-field="Mobile"]').prop('checked', false);

            $(this).find('ModelId').text().length == 4 ? $('.smbios-manlcsel').prop('disabled',true):
                $('.smbios-manlcsel').prop('disabled',false);

            genMacSerial(true, $(this).find('ModelId').text());

            return false;
        }
    });
}

/**
 * Generate Mac Serials Gen 1 and 2
 *
 * @param bool genNew
 * @param string mdlc
 */
function genMacSerial(genNew, mdlc) {
    var modelCode = mdlc != null ? mdlc:$('.smbios-mdl-code').text();
    var selectedYear = $('.smbios-yrsnsel').val();
    var locationCode = modelCode.length == 4 ? getMacNewGenSerialBase($('.smbios-pdname').val()):$('.smbios-manlcsel').val();
    var snYear = modelCode.length == 4 ? '':selectedYear.charAt(3);
    var weekN = Math.floor(Math.random() * (52 - 1 + 1)) + 1;
    var unitN = randomString(3).toUpperCase();
    var fweekN='';

    var smbiosSN = $('.smbios-sn');
    var smbiosWeek = $('.smbios-weekN');
    var smbiosUnitN = $('.smbios-unitN');

    if (!genNew) {
        var currentSerial = smbiosSN.val();

        if (currentSerial.length == 11 && modelCode.length == 3) {
            fweekN = smbiosWeek.val() != '' ? smbiosWeek.val():currentSerial.substr(3,2);
            unitN = smbiosUnitN.val() != '' ? smbiosUnitN.val():currentSerial.substr(-6,3);

        } else if (currentSerial.length == 12 && modelCode.length == 4) {
            fweekN = smbiosWeek.val() != '' ? genMacNewSerialYW(smbiosWeek.val(),selectedYear):currentSerial.substr(3,2);
            unitN = smbiosUnitN.val() != '' ? smbiosUnitN.val():currentSerial.substr(-7,3);
        }
    } else {
        fweekN = weekN.toString().length == 1 ? '0'+weekN.toString():weekN;

        smbiosWeek.val(fweekN);
        smbiosUnitN.val(unitN);

        if (modelCode.length == 4)
            fweekN = genMacNewSerialYW(weekN,selectedYear);
    }

    var serialNumber = locationCode+snYear+fweekN+unitN+modelCode;

    $('.smbios-final-serial').html(serialNumber);
    smbiosSN.val(serialNumber);

    ajax('setval',[smbiosSN.attr('data-path'),smbiosSN.attr('data-field'),smbiosSN.val()]);
}

/**
 * Generate the new YW in Mac Serials Gen 2
 *
 * @param string weekLimit
 * @param string yearLimit
 *
 * @returns {string}
 */
function genMacNewSerialYW(weekLimit,yearLimit) {
    var weekCArr = ['C','D','F','G','H','J','K','L','M','N','P','Q','R','T','V','W','X','Y','1','2','3','4','5','6','7','8','9'];
    var yearCArr = ['0','1','2','3','4','5','6','7','8','9','C','D','F','G','H','J','K','L','M','N','P','Q','R','S','T','V','W','X'];
    var gen2Week='', gen2Yr='';

    for (var w=0,ix=0; ix<weekLimit; ++w, ++ix) {
        if (w == weekCArr.length) w=0;

        gen2Week = weekCArr[w];
    }

    for (var y=2005,yx=0; y<=yearLimit; ++y, yx+=2) {
        if (y == yearLimit) {
            var yearQ = weekLimit >= 27 ? (yx+1):yx;

            gen2Yr = yearCArr[yearQ];
        }
    }

    return gen2Yr+gen2Week;
}

/**
 * Helper function to select the base for a Gen 2 Mac serial
 *
 * @param string model
 *
 * @returns {string}
 */
function getMacNewGenSerialBase(model) {
    var type = model.replace(/([a-z,]+)/g,'').toLocaleLowerCase();
    var base = 'C02';

    switch (type) {
        case 'mbp81':
            base = 'W89';
            break;
        case 'im121':
            base = 'W80';
            break;
        case 'mbp83':
        case 'im122':
            base = 'W88';
            break;
        case 'im141':
        case 'im142':
        case 'im143':
        case 'im144':
            base = 'D25';
            break;
        case 'mp51':
            var bases = ['C07','CK0','CG1'];

            base = bases[randomNumber(0,2)];
            break;
        case 'mm51':
        case 'mm52':
        case 'mm53':
        case 'mm61':
        case 'mm62':
            base = 'C07';
            break;
        case 'mp61':
            base = 'F5K';
            break;
        default:
            break;
    }

    return base;
}

/**
 * Random alpha-numeric string generator - CCE
 *
 * @param int len
 *
 * @returns {string}
 *
 * @Note: http://stackoverflow.com/a/27872144/383904
 */
function randomString(len) {
    var str='', i=0, min=0, max=62, r=0;

    while(i<len) {
        r = Math.random()*(max-min)+min <<0;
        str += String.fromCharCode(r+=r>9?r<36?55:61:48);
        ++i;
    }

    return str;
}

/**
 * Generate a random number
 *
 * @param int min
 * @param int max
 *
 * @returns {number}
 */
function randomNumber(min, max) {
    return Math.floor((Math.random() * max) + min);
}

/**
 * Generate a SIP/Booter Flag
 *
 * @param string type
 *
 * @returns {string}
 */
function genSIPBTRFlag(type) {
    var flag=0, tmp=0;
    var obj = type === 'sip' ? $('.sip-flag-val:checked'):$('.btr-flag-val:checked');

    obj.each(function () {
        flag = 1 << $(this).val();

        if (tmp !== 0)
            flag = flag ^ tmp;

        tmp = flag;
    });

    return flag.toString(16);
}

/**
 * Generate ACPI Loader Mode Flag
 *
 * @param string type
 *
 * @returns {number}
 */
function genAcpiLoaderFlag() {
    var flag=0;

    $('.ozal-flag-val:checked').each(function () {
        flag |= parseInt($(this).val(), 16);
    });

    return flag.toString(16);
}

/**
 * Get Flag Bits
 *
 * @param string flag
 *
 * @returns array bits
 */
function getFlagBits(flag) {
    var intFlag = parseInt(flag, 16);
    var v = intFlag;
    var r = 0;
    var bits = [];

    while (v !== 0) {
        while (v >>= 1)
            ++r;

        v = intFlag ^ 1 << r;
        intFlag = v;
        bits.push(r);
        r = 0;
    }

    return bits;
}

/**
 * Get ACPI Loader Mode Flag Bits
 *
 * @param string flag
 *
 * @returns array bits
 */
function getAcpiLoaderFlagBits(flag) {
    var flag = parseInt(flag, 16);
    var obj = $('.ozal-flag-val');
    var sum = 0;
    var bitmask = 0;
    var bits = [];

    obj.each(function () {
        sum |= parseInt($(this).val(), 16);
        $(this).prop('disabled', false);
    });

    bitmask = sum & flag;

    obj.each(function () {
        if (flag == 0x0) {
            bits.push(0);

            obj.each(function () {
                if ($(this).val() != 0)
                    $(this).prop('disabled', true);
            });

            return false;
        }

        if ($(this).val() != 0 && (bitmask & parseInt($(this).val(), 16)) ) {
            bits.push($(this).val());
        }
    });

    return bits;
}

/**
 * Update flag checkboxes
 *
 * @param string type
 * @param string flag
 */
function updateGUIBits(type, flag) {
    var obj = null, modalt = null;
    var bits;

    switch (type) {
        case 'CsrActiveConfig':
            bits = getFlagBits(flag).sort();
            obj = $('.sip-flag').find('.sip-flag-val');
            modalt = $('.fflag');
            break;
        case 'BooterConfig':
            bits = getFlagBits(flag).sort();
            obj = $('.btr-flag').find('.btr-flag-val');
            modalt = $('.fbflag');
            break;
        case 'AcpiLoaderMode':
            bits = getAcpiLoaderFlagBits(flag).sort();
            obj = $('.ozal-flag').find('.ozal-flag-val');
            modalt = $('.ozalflag');
            break;
        default:
            break;
    }

    obj.each(function () {
        var currentCheck = $(this);
        var stat = false;

        for (var i=0, len=bits.length; i<len; ++i) {
            if (currentCheck.val() == bits[i]) {
                stat = true;
                bits.splice(i,1);
                break;
            }
        }

        stat ? currentCheck.prop('checked', true):currentCheck.prop('checked', false);
    });

    modalt.text(flag);
}

/**
 * Convert a base64 value to hex
 *
 * @param string base64
 *
 * @returns string HEX
 *
 * @info http://stackoverflow.com/questions/39460182/decode-base64-to-hexadecimal-string-with-javascript
 */
function base64ToHEX(base64) {
    var raw = atob(base64);
    var HEX = '';

    for (var i=0, len=raw.length; i<len; ++i) {
        var _hex = raw.charCodeAt(i).toString(16);

        HEX += (_hex.length==2?_hex:'0'+_hex);
    }

    return HEX;
}

/**
 * Convert an hex value to base64
 *
 * @param string hex
 *
 * @returns {string}
 *
 * @info http://stackoverflow.com/questions/23190056/hex-to-base64-converter-for-javascript
 */
function hexToBase64(hex) {
    return btoa(String.fromCharCode.apply(null,
        hex.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" "))
    );
}

/**
 * Check if h is a valid hex string
 *
 * @param string h
 *
 * @returns {boolean}
 */
function isHex(h) {
    var regex = new RegExp('^(0[xX])?[a-fA-F0-9]+$');

    return regex.test(h);
}

/**
 * Switch to Ozmosis Mode
 *
 * @param string toggle
 */
function ozmosize(toggle) {
    var rtDataPath = '', ozElsDataPath = '', noOzSmbiosFeaturesState = '';

    if (toggle == 'oz') {
        rtDataPath = 'Defaults:7C436110-AB2A-4BBB-A880-FE41995C9F82';
        ozElsDataPath = 'Defaults:4D1FDA02-38C7-4A6A-9CC6-4BCCA8B30102';
        noOzSmbiosFeaturesState = true;
    } else {
        rtDataPath = 'RtVariables';
        ozElsDataPath = 'SMBIOS';
        noOzSmbiosFeaturesState = false;
    }

    $('.nooz').each(function () {
        $(this).toggleClass('hidden-el');
    });

    $('.to-oz-path').each(function () {
        $(this).attr('data-path', ozElsDataPath);
    });

    $('.oz-disabled').each(function () {
        $(this).find('label').toggleClass('color-disabled');
    });

    $('.smbios-boardtp, .smbios-locinchass, .smbios-chman, .smbios-platfeatures,' +
        '.smbios-smuuid, .smbios-boardman').prop('disabled', noOzSmbiosFeaturesState);

    $('.cce-text, .cce-combo, .cce-numb').each(function () {
        $(this).val('');
    });

    $('.cce-checkbox:checked').each(function () {
        $(this).prop('checked',false);
    });

    $('.cce-sel').each(function () {
        $(this).prop('selectedIndex',0);
    });

    $('a[aria-controls="ozmosis"]').parent().toggleClass('hidden-el');
    $('.to-oz-rt-path').attr('data-path', rtDataPath);
    $('.smbios-speccpu, .smbios-specgfx, .smbios-specram').html('');
    $('.scrollable').perfectScrollbar('update');

    return;
}

function resetSaveToBankInputs() {
    $('.bank-edit-key-ins, .bank-config-edit-mode, .bank-edit-key').addClass('hidden-el');
    $('input[name="configBankEditMode"]').prop('checked', false);
    $('#bankEditModePub').prop('checked', true);
    $('.saveas-text-err').addClass('hidden-el');
    $('.saveas-text').removeClass('saveas-text-inp-err');
    $('.saveas-text').unbind('keyup');
    $('.cfg-name').prop('disabled', false);
    $('.save-to-bank').prop('checked', false).removeClass('updmode');
    $('.cfg-name').val('');
}

function drawConfigs(filter) {
    var ret = ajax('bsearch', [filter, 'plc'], false, false);
    var html = '';

    for (var i=0, len=ret.length; i<len; ++i) {
        var name = ret[i]['name'].length > 8 ? ret[i]['name'].substr(0, 8)+'..':ret[i]['name'];
        var iconLock = '';

        if (ret[i]['locked'] == 'y')
            iconLock = '<i class="fa icon-lock bank-config-locked"></i>';

        html +=
            '<div class="col-xs-4 col-sm-4 col-md-3">' +
            '<div class="bank-config-box" data-idx="'+ret[i]['id']+'" title="'+ret[i]['name']+'">' +
            '<div class="bank-box-icons">' +
            '<i class="fa icon-document-code bank-config-icon"></i>' +
            iconLock +
            '</div>' +
            '<span>'+name+'</span>' +
            '</div>' +
            '</div>';
    }

    $('.cce-bank-container-editor').find('.row').html(html);
}