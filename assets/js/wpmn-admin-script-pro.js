jQuery(document).ready(function($) {
    
    // Delegate event for dynamic content
    $(document).on('click', '.wpmn_color_option', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var color = $(this).data('color');
        var $dropdown = $(this).closest('.wpmn_color_picker_dropdown');
        
        // Visual updates
        $dropdown.find('.wpmn_custom_color_input').val(color);
        $dropdown.find('.wpmn_current_color_preview').css('background-color', color);
        
        $dropdown.find('.wpmn_color_option').removeClass('selected');
        $(this).addClass('selected');

        // Allow further propagation if needed or trigger a save
        // We trigger a custom event that the main application can listen to
        // passing the color and the folder element if possible.
        // The context menu is usually opened for a specific folder. 
        // We need to find which folder is active.
        
        // Assuming the context menu is attached to the body or a container, 
        // we might not know the target folder directly here unless stored globally.
        // But usually context menus render *for* an item.
        
        $(document).trigger('wpmn_pro_date_color_selected', [color]);
    });

    // Input manual entry
    $(document).on('input', '.wpmn_custom_color_input', function() {
        var color = $(this).val();
        // Basic validation
        if(color.length >= 4 && color.startsWith('#')) {
             $(this).siblings('.wpmn_current_color_preview').css('background-color', color);
        }
    });

    // Prevent menu closing when clicking inside the color picker
    $(document).on('click', '.wpmn_color_picker_dropdown', function(e) {
        e.stopPropagation();
    });

});
