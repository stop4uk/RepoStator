$(function($){
    var Duplicate = function(runData) {
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver,
            observer = new MutationObserver(function(mutations){
                mutations.forEach(function(mutation){
                    addCopyButton(runData);
                });
            }),
            config = {childList: true};

        observer.observe(document.querySelector('#' + runData['blockID']), config);
        addCopyButton(runData);
    }

    var addCopyButton = function(runData)
    {
        $('#' + runData['blockID'] + ' tr').each(function(rowEQ){
            let copyButton = $('<span>', {
                type: 'button',
                html: '<i class="bi bi-fullscreen"></i>',
                'class': 'p-1 copy-button ' + runData['buttonClass'],
                'data-block': runData['blockID'],
                'data-row': rowEQ,
                'data-filter': runData['filterDuplicateElements']
            }).on('click', function(){ setDuplicateValue($(this)); });

            $(this).find('td').eq(runData['columnForButton']).not(':has(span.copy-button)').append(copyButton);
        });
    }

    var setDuplicateValue = function(element)
    {
        let blockID = element.attr('data-block'),
            rowEQ = element.attr('data-row'),
            filter = element.attr('data-filter'),
            filterRow = 'input:not(.' + filter + '), select:not(.' + filter + '), textarea:not(.' + filter + ')',
            agree = confirm('Вы уверены, что хотите растиражировать значения строки?');

        if (agree) {
            $("#" + blockID + ' tr').eq(rowEQ).each(function(index){
                $(this).find('td').each(function(index){
                    let columnIndex = index;

                    $(this).find(filterRow).each(function(index){
                        let setValue = ($(this).is('input') || $(this).is('textarea'))
                                ? $(this).val()
                                : $(this).find('option').filter(':selected').val();

                        $("#" + blockID + " tr").not(':eq(' + rowEQ + ')').each(function(){
                            $(this).find('td').eq(columnIndex).find(filterRow).eq(index).val(setValue).trigger('change');
                        });
                    });
                });
            });
        }
    }

    window.duplicate = Duplicate;
});
