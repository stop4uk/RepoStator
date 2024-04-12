$(function($){
    var Repeater = function(url){
        var appendUrl = url.append,
            deleteUrl = url.remove,
            self = this,
            lastIndex = Number($(".repeater-item:last").attr("data-id")),
            allRows = $(".repeater-item").length;
        
        lastIndex++;
        this.id = lastIndex;
        this.archive = [];
        
        var $wrap = $('.ab-repeater .list-area'),
            $recover = $('.ab-repeater .recover-btn');
        
        this.recover = function(){
            $wrap.append(this.archive.pop());
            if(this.archive.length === 0){
                $recover.prop('disabled', true);
            }
        };

        if (allRows == 1)  {
            $(".remove").addClass('d-none');
        }

        $(document).on('click', '.repeater-item .remove', function(){
            var countRemoveButtons = $(".repeater-item").length
            self.archive.push($(this).parents('.repeater-item').clone());
            var $item = $(this).parents('.repeater-item');
            var data ={id:$item.data('id')};
            $.post(deleteUrl,data, function(data){
                $item.remove();
                $recover.prop('disabled', false);

                if (countRemoveButtons == 2)  {
                    $(".remove").addClass('d-none');
                }
            });
        });

        $('.new-repeater').click(function(){
            var data ={id:self.id};
            data[yii.getCsrfParam()]=yii.getCsrfToken();
            data.id = self.id;
            data.additionalData = $('.repeater-item').find('input,select,textarea').serialize();
            data.additionalField = $('#additionalField').val();

            data.buttonDeleteData = {
                'buttonDeleteName': $('.remove').html(),
                'buttonDeleteClasses': $('.remove').attr('class'),
                'buttonDeletePlaceBlock': $("#buttonDeleteBlock").attr("class")
            };

            $.post(appendUrl, data, function(data){
                $wrap.append($(data));
                if (allRows == 1) {
                    $('.remove').removeClass('d-none');
                }
            });

            self.id++;
        });
        
        $recover.click(function(){
            self.recover();
        })
    };

    window.repeater = Repeater;
});