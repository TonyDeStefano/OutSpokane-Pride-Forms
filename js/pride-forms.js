(function($){

    $('#pride-form-container').on('click', '#btn-step-1', function(){

        getPrideForm();

        var error = '';

        var required_values = [
            ['First Name', getPrideFormValue('first_name')],
            ['Last Name', getPrideFormValue('last_name')],
            ['Email', getPrideFormValue('email')],
            ['Phone', getPrideFormValue('phone')],
            ['Address', getPrideFormValue('address')],
            ['City', getPrideFormValue('city')],
            ['State', getPrideFormValue('state')],
            ['Zip', getPrideFormValue('zip')]
        ];

        for (var v=0; v<required_values.length; v++) {

            if (error.length == 0 && required_values[v][1].length == 0) {
                error = required_values[v][0] + ' is required';
            }
        }

        if (error.length > 0) {

            alert(error);

        } else {

            var post = {
                action: 'pride_entry',
                entry_nonce: prideforms.entry_nonce,
                entry_year: pride_form.year,
                form: pride_form.form,
                organization: getPrideFormValue('organization'),
                first_name: getPrideFormValue('first_name'),
                last_name: getPrideFormValue('last_name'),
                email: getPrideFormValue('email'),
                phone: getPrideFormValue('phone'),
                address: getPrideFormValue('address'),
                city: getPrideFormValue('city'),
                state: getPrideFormValue('state'),
                zip: getPrideFormValue('zip')
            };

            if (pride_form.form == 'cruise') {
                post.qty = getPrideFormValue('qty')
            } else if (pride_form.form == 'festival') {
                post.qty = 1;
                post.entry_type_id = getPrideFormValue('entry_type_id');
                post.corner_booth = getPrideFormValue('corner_booth');
            }

            $.ajax({
                url: prideforms.ajax_url,
                type: 'POST',
                data: post,
                error: function(x, y, z) {
                    console.log(x);
                    console.log(y);
                    console.log(z);
                    alert('There was an error. Please try again.');
                },
                success: function(json) {
                    if (json.success == '0') {
                        alert(json.error);
                    } else {
                        window.location = '?txid=' + json.txid + '#confirmation-payment';
                    }
                }
            });
        }
    });

    $('#entry_type_id').change(function(){

        var sponsor_id = $(this).data('sponsor-id');
        var container = $('#corner-booth-container');

        if ($(this).val() == sponsor_id)
        {
            container.hide();
        }
        else
        {
            container.show();
        }
    });

})(jQuery);

var pride_form = {
    form: '',
    year: '',
    fields: []
};

function getPrideForm()
{
    pride_form = {
        form: '',
        year: '',
        price: 0,
        fields: []
    };

    var container = jQuery('#pride-form-container');
    if (container.length > 0)
    {
        pride_form.form = container.data('form');
        pride_form.year = container.data('year');

        container.find('.form-control').each(function(){
            pride_form.fields.push({
                'id' : jQuery(this).attr('id'),
                'label' :  jQuery(this).data('label'),
                'value' : jQuery(this).val()
           });
        });
    }
}

function getPrideFormValue(id) {

    for (var i=0; i<pride_form.fields.length; i++) {
        if (pride_form.fields[i].id == id) {
            return pride_form.fields[i].value;
        }
    }

    return '';
}
