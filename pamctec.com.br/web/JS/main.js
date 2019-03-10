/*JavaScript Pamtec*/
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