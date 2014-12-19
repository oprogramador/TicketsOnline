function totalPriceUpdate() {
    var childs = $('#mondo_bookingbundle_customer_childs').val()*1;
    var adults = $('#mondo_bookingbundle_customer_adults').val()*1;
    var seniors = $('#mondo_bookingbundle_customer_seniors').val()*1;
    $('#totalPrice').html( childs*childPrice + adults*adultPrice + seniors*seniorPrice );
}
$(document).ready(function() {
    totalPriceUpdate();
    $('#mondo_bookingbundle_customer_childs').change(totalPriceUpdate);
    $('#mondo_bookingbundle_customer_adults').change(totalPriceUpdate);
    $('#mondo_bookingbundle_customer_seniors').change(totalPriceUpdate);
});
