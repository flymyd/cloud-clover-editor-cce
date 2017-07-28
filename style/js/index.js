/*!
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * Licensed under GPLv3 license
 */
'use strict';

$(function() {
    $(document).on('change', '#file-upload', function () {
        $(this).parent().submit();
    });

    $(document).on('click', '.cce-bank-btn', function () {
        if ($('.cce-bank').hasClass('hidden-el')) {
            drawConfigs('listall');

            $('.cce-load-cfg').addClass('hidden-el');
            $('.cce-bank').removeClass('hidden-el');
            $('.cce-bank-t').addClass('hidden-el');
            $('.cce-open-t').removeClass('hidden-el');
        } else {
            $('.cce-load-cfg').removeClass('hidden-el');
            $('.cce-bank').addClass('hidden-el');
            $('.cce-bank-t').removeClass('hidden-el');
            $('.cce-open-t').addClass('hidden-el');
        }
    });

    $(document).on('click', '.bank-config-box', function () {
        $('#cceBankForm').find('input[name="bid"]').val($(this).attr('data-idx'));
        $('#cceBankForm').submit();
    });

    $(document).on('keyup', '.cce-bank-search', function () {
        var filt = $(this).val() == '' ? 'listall':$(this).val();

        drawConfigs(filt);
    });
});

function ajax(type,vals) {
    var result = null;

    if (type != null && vals != null) {
        $.ajax({
            method: "POST",
            dataType: "json",
            cache: false,
            async: false,
            url: 'data/ajx/write.php',
            data: {type:type, vals:vals},
            success: function (re) {
                result = re;
            }
        });
    }

    return result;
}

function drawConfigs(filter) {
    var ret = ajax('bsearch', [filter, 'plc']);
    var html = '';

    for (var i=0, len=ret.length; i<len; ++i) {
        var name = ret[i]['name'].length > 8 ? ret[i]['name'].substr(0, 8)+'..':ret[i]['name'];
        var iconLock = '';

        if (ret[i]['locked'] == 'y')
            iconLock = '<i class="fa icon-lock bank-config-locked"></i>';

        html +=
            '<div class="col-xs-4 col-sm-2 col-md-1">' +
            '<div class="bank-config-box" data-idx="'+ret[i]['id']+'" title="'+ret[i]['name']+'">' +
            '<div class="bank-box-icons">' +
            '<i class="fa icon-document-code bank-config-icon"></i>' +
            iconLock +
            '</div>' +
            '<span>'+name+'</span>' +
            '</div>' +
            '</div>';
    }

    $('.cce-bank-container').find('.row').html(html);
}