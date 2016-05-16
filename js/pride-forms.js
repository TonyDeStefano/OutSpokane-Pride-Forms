var pride_entry_types;
var pride_form_is_processing = false;

(function($){

    $('#pride-form-container').on('click', '#btn-step-1', function(){

        if (pride_form_is_processing) {
            return;
        }

        pride_form_is_processing = true;
        $(this).text('Please wait ...');

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

        if (pride_form.form == 'donation') {
            var donation = getPrideFormValue('donation_amount');
            if (donation.length == 0) {
                error = 'Donation Amount is required';
            }
        }

        if (error.length > 0) {

            alert(error);
            pride_form_is_processing = false;
            $(this).text('Submit');

        } else {

            var post = {
                action: 'pride_entry',
                path: $(location).attr('pathname'),
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
                zip: getPrideFormValue('zip'),
                qty: 1
            };

            if (pride_form.form == 'cruise') {
                post.qty = getPrideFormValue('qty')
            } else if (pride_form.form == 'festival') {
                post.entry_type_id = getPrideFormValue('entry_type_id');
                post.corner_booth = getPrideFormValue('corner_booth');
                post.description = getPrideFormValue('description');
            } else if (pride_form.form == 'parade') {
                pride_entry_types = [];
                $('.parade_entry_type').each(function () {
                    if ($(this).prop('checked')) {
                        pride_entry_types.push($(this).val());
                    }
                });
                post.entry_types = JSON.stringify(pride_entry_types);
                post.float_parking_spaces = getPrideFormValue('float_parking_spaces');
                post.donation_amount = getPrideFormValue('donation_amount');
                post.description = getPrideFormValue('description');
                post.needs_amped_sound = getPrideFormValue('needs_amped_sound');
                post.group_size = getPrideFormValue('group_size');
            } else if (pride_form.form = 'donation') {
                post.donation_amount = getPrideFormValue('donation_amount');
            } else if (pride_form.form == 'murder_mystery') {
                post.is_sponsor = $('#is_sponsor').val();
                if (post.is_sponsor == '1') {
                    post.qty = '1';
                    if ($('#sponsor_meal_type_upgraded').prop('checked')) {
                        post.is_upgraded = '1';
                        post.vegetarian_qty = 0;
                    } else {
                        post.is_upgraded = '0';
                        post.vegetarian_qty = $('#sponsor_vegetarian_qty').val();
                    }
                } else {
                    post.qty = $('#ticket_qty').val();
                    if ($('#ticket_meal_type_upgraded').prop('checked')) {
                        post.is_upgraded = '1';
                        post.vegetarian_qty = 0;
                    } else {
                        post.is_upgraded = '0';
                        post.vegetarian_qty = $('#ticket_vegetarian_qty').val();
                    }
                }
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
                    pride_form_is_processing = false;
                    $('#btn-step-1').text('Submit');
                },
                success: function(json) {
                    if (json.success == '0') {
                        alert(json.error);
                        pride_form_is_processing = false;
                        $('#btn-step-1').text('Submit');
                    } else {
                        console.log(json);
                        pride_form_is_processing = false;
                        $('#btn-step-1').text('Submit');
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

    $('#is_sponsor').change(function(){
        doSponsorChange();
    });

    doSponsorChange();

    var mm_ticket_fields = $('#mm-ticket-fields');

    mm_ticket_fields.on('change', '#ticket_qty', function(){
        fillVegNumbers();
    });

    fillVegNumbers();

    mm_ticket_fields.on('change', 'input[name=ticket_meal_type]:radio', function(){
       showHideTicketVeg();
    });

    showHideTicketVeg();

    var mm_sponsor_fields = $('#mm-sponsor-fields');

    mm_sponsor_fields.on('change', 'input[name=sponsor_meal_type]:radio', function(){
        showHideSponsorVeg();
    });

    showHideSponsorVeg();

})(jQuery);

var pride_form = {
    form: '',
    year: '',
    fields: []
};

function fillVegNumbers()
{
    var tix = jQuery('#mm-ticket-fields').find('#ticket_qty');
    if (tix.length) {
        var val = parseInt(tix.val());
        var el = jQuery('#ticket_vegetarian_qty');
        el.html('');
        for (var v = 0; v <= val; v++) {
            el.append('<option value="' + v + '">' + v + '</option>');
        }
    }
}

function showHideTicketVeg()
{
    var container = jQuery('#mm-ticket-fields');
    var veg = jQuery('#mm-vegetarian-dinners');
    if (container.length) {
        if (jQuery('#ticket_meal_type_upgraded').prop('checked')) {
            veg.hide();
        } else {
            veg.show();
        }
    }
}

function showHideSponsorVeg()
{
    var container = jQuery('#mm-sponsor-fields');
    var veg = jQuery('#sponsor-vegetarian-dinners');
    if (container.length) {
        if (jQuery('#sponsor_meal_type_upgraded').prop('checked')) {
            veg.hide();
        } else {
            veg.show();
        }
    }
}

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

function doSponsorChange(){
    var el = jQuery('#is_sponsor');
    if (el.length > 0) {
        var val = el.val();
        var sponsor = jQuery('#mm-sponsor-fields');
        var tickets = jQuery('#mm-ticket-fields');
        if (val == '1') {
            sponsor.show();
            tickets.hide();
        } else {
            sponsor.hide();
            tickets.show();
        }
    }
}
