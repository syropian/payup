//Test
//Stripe.setPublishableKey('YourTestPublishableKey');

//Production
Stripe.setPublishableKey('YourProductionPublishableKey');

function stripeResponseHandler(status, response) {
    if (response.error) {
        // re-enable the submit button
        $('.submit-button').removeAttr("disabled");
        // show the errors on the form
        $(".payment-errors").html(response.error.message);
    } else {
        var form$ = $("#payment-form");
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        form$.append("<input type='hidden' name='chargeAmount' value='"+ $('.charge-amount').val()*100 +"' />");

        // and submit
        form$.get(0).submit();
    }
}

$(document).ready(function() {
    $('.card-number').formatCardNumber();
    $('.card-cvc').formatCardCVC();
    $('.card-expiry').formatCardExpiry();
    $('.charge-amount').restrictNumeric();

    $('input[type=text]').focus(function(){
        $(this).removeClass('error');
    });

    function isEmpty(aString) {
        return aString.replace(/\s/g,"") == "";
    }

    $("#payment-form").submit(function(event) {
        //Check for errors
        var hasErrors = false;
        var expData = $('.card-expiry').cardExpiryVal();
        var isValidCardNumber = $.validateCardNumber($('.card-number').val());
        var isValidCVC = $.validateCardCVC($('.card-cvc').val());
        var isValidExpiry = $.validateCardExpiry(expData['month'], expData['year']);


        if(isEmpty($('.buyer-name').val())){
            $('.buyer-name').addClass('error');
            hasErrors = true;
        }
        else {
            $('.buyer-name').removeClass('error');
        }

        if(isEmpty($('.charge-amount').val())){
            $('.charge-amount').addClass('error');
            hasErrors = true;
        }
        else {
            $('.charge-amount').removeClass('error');
        }

        if(!isValidCardNumber || isEmpty($('.card-number').val())){
            $('.card-number').addClass('error');
            hasErrors = true;
        }
        else {
            $('.card-number').removeClass('error');
        }

        if(!isValidCVC || isEmpty($('.card-cvc').val())){
            $('.card-cvc').addClass('error');
            hasErrors = true;
        }
        else {
            $('.card-cvc').removeClass('error');
        }

        if(!isValidExpiry || isEmpty($('.card-expiry').val())){
            $('.card-expiry').addClass('error');
            hasErrors = true;
        }
        else {
            $('.card-expiry').removeClass('error');
        }

        if(!hasErrors){
            // disable the submit button to prevent repeated clicks
            $('.submit-button').attr("disabled", "disabled");

            // createToken returns immediately - the supplied callback submits the form if there are no errors

            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: expData['month'],
                exp_year: expData['year'],
                name: $('.buyer-name').val()
            }, stripeResponseHandler);
        }
            return false; // submit from callback
    });
});
