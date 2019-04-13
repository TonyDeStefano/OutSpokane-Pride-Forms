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

            if (error.length === 0 && required_values[v][1].length === 0) {
                error = required_values[v][0] + ' is required';
            }
        }

        if (pride_form.form === 'donation') {
            var donation = getPrideFormValue('donation_amount');
            if (donation.length === 0) {
                error = 'Donation Amount is required';
            }
        }

        if (pride_form.form === 'sponsorship') {
            var sponsorship_amount = getPrideFormValue('amount');
            if (sponsorship_amount.length === 0) {
                error = 'Sponsorship Amount is required';
            }
        }

        if (pride_form.form === 'flag') {
            var message = getPrideFormValue('message').replace(/\W/g, '');
            if (message.length > 25) {
                error = 'Your embroidered name must be 25 characters or less (not including spaces and periods)'
            }
        }

        var mm_qty = 1;
        var mm_is_sponsor = 0;
        var mm_is_vip = 0;
        var mm_meals = {};

        if (pride_form.form === 'murder_mystery') {

            mm_qty = $('#ticket_qty').val();
            if ($('#mm_is_sponsor').val() === '1') {
                mm_qty = 10;
                mm_is_sponsor = 1;
            } else if ($('#mm_is_vip').val() === '1') {
                mm_qty = 8;
                mm_is_vip = 1;
            } else {
                mm_qty = parseInt(mm_qty);
            }

            var meal_qty = 0;
            $('.meal_qty').each(function(){
                var q = $(this).val();
                q = q.replace(/[^0-9]/g, '');
                if(q.length === 0){
                    q = '0';
                }
                if(q > 0){
                    mm_meals[$(this).data('index')] = q;
                }
                meal_qty += parseInt(q);
            });

            if (meal_qty !== mm_qty) {
                error = 'The number of meals needs to add up to the number of tickets'
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

            if (pride_form.form === 'cruise' || pride_form.form === 'bowling') {
                post.qty = getPrideFormValue('qty');
                post.is_will_call = getPrideFormValue('is_will_call');
            } else if (pride_form.form === 'festival') {
                post.entry_type_id = getPrideFormValue('entry_type_id');
                post.corner_booth = getPrideFormValue('corner_booth');
                post.description = getPrideFormValue('description');
            } else if (pride_form.form === 'food_truck') {
                post.description = getPrideFormValue('description');
            } else if (pride_form.form === 'parade') {
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
            } else if (pride_form.form === 'donation') {
                post.donation_amount = getPrideFormValue('donation_amount');
            } else if (pride_form.form === 'flag') {
                post.message = getPrideFormValue('message');
                post.color = getPrideFormValue('color');
            } else if (pride_form.form === 'murder_mystery') {
                post.qty = mm_qty;
                post.is_sponsor = mm_is_sponsor;
                post.is_vip = mm_is_vip;
                post.meals = mm_meals;
            } else if (pride_form.form === 'sponsorship') {
                post.amount = getPrideFormValue('amount');
                post.position = getPrideFormValue('position');
                post.url = getPrideFormValue('url');
                post.local_email = getPrideFormValue('local_email');
                post.local_phone = getPrideFormValue('local_phone');
                post.local_position = getPrideFormValue('local_position');
                post.local_first_name = getPrideFormValue('local_first_name');
                post.local_last_name = getPrideFormValue('local_last_name');
                post.local_address = getPrideFormValue('local_address');
                post.local_city = getPrideFormValue('local_city');
                post.local_state = getPrideFormValue('local_state');
                post.local_zip = getPrideFormValue('local_zip');
            }

            $.ajax({
                url: prideforms.ajax_url,
                type: 'POST',
                data: post,
                error: function(x, y, z) {
                    console.log(x);
                    console.log(x.responseText);
                    console.log(y);
                    console.log(z);
                    alert('There was an error. Please try again.');
                    pride_form_is_processing = false;
                    $('#btn-step-1').text('Submit');

                    $.ajax({
                        url: prideforms.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'error-report',
                            error: x.responseText
                        }
                    });
                },
                success: function(json) {
                    if (json.success == 0) {
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

        if ($(this).val() === sponsor_id)
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

    /* Murder Mystery Form */

    refreshMurderMysteryForm();

    $('#mm_is_sponsor, #mm_is_vip').change(function(){
        refreshMurderMysteryForm();
    });

})(jQuery);

var pride_form = {
    form: '',
    year: '',
    fields: []
};

function refreshMurderMysteryForm(){

    var qty = jQuery('#mm-qty-question').find('#ticket_qty');
    var preset_qty = false;

    if(jQuery('#mm_is_sponsor').val() === '1'){
        jQuery('#mm-vip-question').hide();
        preset_qty = true;
        qty.closest('div').prepend('<span>10</span>');
    } else {
        jQuery('#mm-vip-question').show();
    }

    if(jQuery('#mm_is_vip').val() === '1'){
        jQuery('#mm-sponsor-question').hide();
        preset_qty = true;
        qty.closest('div').prepend('<span>8</span>');
    } else {
        jQuery('#mm-sponsor-question').show();
    }

    if(preset_qty) {
        qty.hide();
    } else {
        qty.closest('div').find('span').remove();
        qty.show();
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
