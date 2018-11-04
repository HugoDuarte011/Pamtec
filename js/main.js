/*JavaScript Pamtec*/

$(function(){
    $('a[href="#"]').on('click', function(e){
        e.preventDefault();
    });
    
    $('#menu > li').on('mouseover', function(e){
        $(this).find("ul:first").show();
        $(this).find('> a').addClass('active');
    }).on('mouseout', function(e){
        $(this).find("ul:first").hide();
        $(this).find('> a').removeClass('active');
    });
    
    $('#menu li li').on('mouseover',function(e){
        if($(this).has('ul').length) {
            $(this).parent().addClass('expanded');
        }
        $('ul:first',this).parent().find('> a').addClass('active');
        $('ul:first',this).show();
    }).on('mouseout',function(e){
        $(this).parent().removeClass('expanded');
        $('ul:first',this).parent().find('> a').removeClass('active');
        $('ul:first', this).hide();
    });
});

// Aplica a mascara na caixa de CNPJ
$(document).ready(function(){
    $("#cnpj").mask("99.999.999/9999-99");

    $(document).on('keypress', '#cnpj', function(e) {
    var square = document.getElementById("cnpj");
        var key = (window.event)?event.keyCode:e.which;
        if((key > 47 && key < 58)) {
            return true;
        } else {
            return (key == 8 || key == 0)?true:false;
        }
    });

});