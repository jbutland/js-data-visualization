(function($) {
    'use strict';
    $(document).ready(function()
		{
        $('#instances').change(function()
				{
            var optionSelected = $(this).find("option:selected");
            var valueSelected = optionSelected.val();
            var textSelected = optionSelected.text();
            alert(valueSelected);
        });
    });

})(jQuery);
