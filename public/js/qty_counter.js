jQuery(document).ready(function(){
    $('#spinner button').on('click', function(){
        let input = $(this).closest('#spinner').find('input[name=qty]');

        if($(this).data('action') === 'increment') {
            if(input.attr('max') === undefined || parseInt(input.val()) < parseInt(input.attr('max'))) {
                input.val(parseInt(input.val(), 10) + 1);
            }
        } else if($(this).data('action') === 'decrement') {
            if(input.attr('min') === undefined || parseInt(input.val()) > parseInt(input.attr('min'))) {
                input.val(parseInt(input.val(), 10) - 1);
            }
        }
    });
});