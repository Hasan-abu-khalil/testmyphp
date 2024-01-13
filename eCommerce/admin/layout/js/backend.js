$(function () {

    var passField = $('.password');
    $('.show-pass').hover(function () {
        passField.attr('type', 'text');
    }, function () {
        passField.attr('type', 'password')
    });


    // Confirmation message 
    $(".confirm").click(function(){

        return confirm('Are You Sure?');
    });




    

})