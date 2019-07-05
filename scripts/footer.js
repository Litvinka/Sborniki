$(document).ready(function(){
    if(($(document).outerHeight()-$("#footer").outerHeight())<$(window).height()){
        $("#footer").addClass('footer-fixed');
    }    
});