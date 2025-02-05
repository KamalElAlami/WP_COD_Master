jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });
    
    // Add new field
    $('#add-field').click(function() {
        var index = $('.custom-field').length;
        var newField = `
            <div class="custom-field">
                <input type="text" 
                       name="cod_form_custom_fields[${index}][label]" 
                       placeholder="Field Label">
                       
                <select name="cod_form_custom_fields[${index}][type]">
                    <option value="text">Text</option>
                    <option value="tel">Phone</option>
                    <option value="email">Email</option>
                    <option value="textarea">Textarea</option>
                </select>
                
                <label>
                    <input type="checkbox" 
                           name="cod_form_custom_fields[${index}][required]"> Required
                </label>
                
                <label>
                    <input type="checkbox" 
                           name="cod_form_custom_fields[${index}][enabled]" 
                           checked> Enabled
                </label>
                
                <button type="button" class="remove-field">Remove</button>
            </div>
        `;
        $('#custom-fields').append(newField);
    });
    
    // Remove field
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.custom-field').remove();
    });
});