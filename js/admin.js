(function($){

    $(function(){

        $('#pride-forms-update-entry-notes').find('#submit').click(function(e){

            e.preventDefault();
            var notes = $('#entry-notes').val();
            var form = $(this).closest('.well').data('form');
            var id = $(this).closest('.well').data('id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pride_forms_update_notes',
                    form: form,
                    notes: notes,
                    id: id
                },
                success: function(x) {
                    if (x == '0') {
                        alert('There was an error. Please try again.');
                    }
                    else {
                        alert('Notes have been saved!');
                    }
                },
                error: function (x, y, z) {
                    console.log(x);
                    console.log(y);
                    console.log(z);
                    alert('There was an error. Please try again.');
                }
            });

        });

        $('#pride-forms-update-details').find('#submit').click(function(e){

            e.preventDefault();
            var form = $(this).closest('.well').data('form');
            var id = $(this).closest('.well').data('id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pride_forms_update_details',
                    form: form,
                    vegetarian_qty: $('#vegetarian_qty').val(),
                    tickets_sent: $('#tickets_sent').val(),
                    id: id
                },
                success: function(x) {
                    if (x == '0') {
                        alert('There was an error. Please try again.');
                    } else {
                        location.reload();
                    }
                },
                error: function (x, y, z) {
                    console.log(x);
                    console.log(y);
                    console.log(z);
                    alert('There was an error. Please try again.');
                }
            });
        });

        $('#pride-forms-mark-as-paid').find('#submit').click(function(e){

            e.preventDefault();
            var payment_method_id = $('#payment-method-id').val();
            var form = $(this).closest('.well').data('form');
            var id = $(this).closest('.well').data('id');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pride_forms_update_payment',
                    form: form,
                    payment_method_id: payment_method_id,
                    id: id
                },
                success: function(x) {
                    if (x == '0') {
                        alert('There was an error. Please try again.');
                    } else {
                        location.reload();
                    }
                },
                error: function (x, y, z) {
                    console.log(x);
                    console.log(y);
                    console.log(z);
                    alert('There was an error. Please try again.');
                }
            });

        });

        $('#pride-delete-entry').click(function(e){

            e.preventDefault();
            var b = confirm('Are you sure you want to delete this entry?');
            if (b){
                var form = $(this).data('form');
                var id = $(this).data('id');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pride_forms_delete_entry',
                        form: form,
                        id: id
                    },
                    success: function(x) {
                        if (x == '0') {
                            alert('There was an error. Please try again.');
                        } else {
                            location.href = '?page=' + x;
                        }
                    },
                    error: function (x, y, z) {
                        console.log(x);
                        console.log(y);
                        console.log(z);
                        alert('There was an error. Please try again.');
                    }
                });
            }
        });

    });

})(jQuery);