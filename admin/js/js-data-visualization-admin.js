(function($) {
    'use strict';
    $(document).ready(function() {
        $('#instances').change(function() {
            var optionSelected = $(this).find("option:selected");
            var valueSelected = optionSelected.val();
            var textSelected = optionSelected.text();
            //alert(valueSelected);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    'instance_id': valueSelected,
                    'action': 'get_instance_questions'
                },
                success: function(data) {
                    $('#questions_display').html(data);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });

        });
    });

})(jQuery);
