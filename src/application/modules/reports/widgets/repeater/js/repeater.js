var repeater = function () {};
(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) { // jshint ignore:line
        // AMD. Register as an anonymous module.
        define(['jquery'], factory); // jshint ignore:line
    } else { // noinspection JSUnresolvedVariable
        if (typeof module === 'object' && module.exports) { // jshint ignore:line
            // Node/CommonJS
            // noinspection JSUnresolvedVariable
            module.exports = factory(require('jquery')); // jshint ignore:line
        } else {
            // Browser globals
            factory(window.jQuery);
        }
    }
}(function($){
    'use strict';
    repeater = function(runData){
        var widgetID = runData.widgetID,
            template = runData.template,
            appendUrl = runData.append,
            deleteUrl = runData.remove,
            additionalInformation = runData.additionalInformation,
            lastRow = Number($(".repeater-item_" + widgetID + ":last").attr("data-id")),
            lastIndex = isNaN(lastRow) ? 0 : ++lastRow,
            wrap = $('.ab-repeater_' + widgetID + ' .list-area');

        $('.new-repeater_' + widgetID).click(function(e){
            e.preventDefault();

            let data = {
                id: lastIndex,
                widgetID: widgetID,
                template: template,
                additionalInformation: additionalInformation,
                additionalData: $('.repeater-item_' + widgetID).find('input,select,textarea').serialize(),
                additionalField: $('#additionalField_' + widgetID).val() ?? '',
                buttonDeleteData: {
                    'buttonDeleteName': $('.repeater-item_' + widgetID + ' .remove').text() ?? '',
                    'buttonDeleteClasses': $('.repeater-item_' + widgetID + ' .remove').attr('class') ?? '',
                    'buttonDeletePlaceBlock': $("#buttonDeleteBlock_" + widgetID).attr("class") ?? ''
                }
            };

            data[yii.getCsrfParam()]=yii.getCsrfToken();
            $.post(appendUrl, data, function(data) {
                let insertData = (
                    (template == 'table')
                        ? '<tr>' + data + '</tr>'
                        : data
                );

                wrap.append(insertData);
            });

            lastIndex++;
        });

        $(document).on('click', '.repeater-item_' + widgetID + ' .copy', function(e){
            e.preventDefault();

            let areaForFindElements = $(this).parents('.repeater-item_' + widgetID),
                data = {
                    id: lastIndex,
                    widgetID: widgetID,
                    template: template,
                    additionalInformation: additionalInformation,
                    additionalData: $('.repeater-item_' + widgetID).find('input,select,textarea').serialize(),
                    additionalField: $('#additionalField_' + widgetID).val(),
                    buttonDeleteData: {
                        'buttonDeleteName': $('.repeater-item_' + widgetID + ' .remove').html(),
                        'buttonDeleteClasses': $('.repeater-item_' + widgetID + ' .remove').attr('class'),
                        'buttonDeletePlaceBlock': $("#buttonDeleteBlock_" + widgetID).attr("class")
                    }
                };

            data[yii.getCsrfParam()]=yii.getCsrfToken();
            $.post(appendUrl, data, function(data) {
                let insertData = (
                    (template == 'table')
                        ? '<tr>' + data + '</tr>'
                        : data
                );

                wrap.append(insertData);
                areaForFindElements.find('input,select,textarea').not('.noncopyable').each(function(){
                    let elementID =  $(this).attr('id');
                    if (elementID) {
                        let elementKey = $(this).attr('id').replace(/-\d+-/, "-" + (lastIndex-1) + "-"),
                            elementValue = $(this).val();
                        $("#" + elementKey).val(elementValue).trigger('change');
                    }
                });
            });

            lastIndex++;
        });

        $(document).on('click', '.repeater-item_' + widgetID + ' .remove', function(){
            let $item = $(this).parents('.repeater-item_' + widgetID),
                data ={
                    id:$item.data('id'),
                    record:$item.attr('recordID')
                };

            $.post(deleteUrl,data, function(data){
                $item.remove();
            });
        });
    };
}));