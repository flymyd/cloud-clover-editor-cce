/*!
 * Cloud Clover Editor
 * Copyright (C) kylon - 2016-2017
 * Licensed under GPLv3 license
 */
'use strict';

(function ($) {
    $.fn.combobox = function () {

        return this.each(function () {
            var options = $(this).attr('data-combo').split(',');
            var optionsCode = '<img src="../style/img/select-arrow.png" class="combobox-arrow" />'+
                              '<div class="combobox-opt-container combobox-hide">';

            $(this).addClass('combobox-input');
            $(this).appendTo(
                $('<div class="combobox"></div>').insertBefore($(this))
            );

            for (var i=0, len=options.length; i<len; ++i) {
                optionsCode += '<div class="combobox-opt">'+options[i]+'</div>';
            }
            optionsCode += '</div>';

            $(optionsCode).insertAfter($(this));

        });
    };

    $(document).on('click','html',function () {
        closeAll();
    });

    $(document).on('click','.combobox-arrow',function (e) {
        e.stopPropagation();

        var el = $(this).parent().find('.combobox-opt-container');

        closeAll(el);

        el.hasClass('combobox-hide') ? el.removeClass('combobox-hide') : el.addClass('combobox-hide');
    });

    $(document).on('click','.combobox-opt',function(e) {
        if ($(this).parent().parent().find('.combobox-input').val() !== $(this).text()) {
            $(this).parent().parent().find('.combobox-input').val($(this).text());
        }

        return $(this).trigger( $.extend({}, e, { type: 'combo-change' }) );
    });

    function closeAll(exclude) {
        var visible = $('.combobox-opt-container').not('.combobox-hide');

        visible.each(function () {
            if (exclude == null || !$(this).is(exclude))
                $(this).addClass('combobox-hide');
        });
    }
}(jQuery));