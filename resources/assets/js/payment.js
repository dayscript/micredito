require('jquery-validation');
require('jquery-mask-plugin');

var accounting = require('accounting');
$('form').submit(false);

$(document).ready(function(){

    $( '.interrogation' ).tooltip();

    var colfuturo_payment = [
        $('#'+'input_cop'),
        $('#'+'input_usd'),
    ]

    var colfuturo_option_one = $('#opt_pay_col')
    var colfuturo_option_two = $('#opt_pay_otr')

    $('.second-options').css('display','none');

    disableElements(colfuturo_payment)

    formatingElemets(colfuturo_payment)

    if($(colfuturo_option_one).is(':disabled')){
       $(colfuturo_option_two).attr('checked',true); 
       enableElements(colfuturo_payment);
    }


    colfuturo_option_one.change(colfuturo_payment,function(){
        if( $(this)[0].checked ){
            disableElements(colfuturo_payment)
            $('.second-options').css('display','none');
         
        }
    })

    colfuturo_option_two.change(colfuturo_payment,function(){
        if( $(this)[0].checked ){
            enableElements(colfuturo_payment)
            $('.second-options').css('display','block');
        }
    })

    colfuturo_payment[0].keyup(colfuturo_payment,function(){
        
        var trm = $('#trm').val().replace(',','');
        var value = $(this).val().replace(/\,/g,'');
        $(colfuturo_payment[1]).val( accounting.formatMoney(Math.round10( (value / trm), -2) ) )

    })

    colfuturo_payment[1].keyup(colfuturo_payment,function(){
        var trm = $('#trm').val().replace(',','');
        var value = $(this).val().replace(/\,/g,'');
        $(colfuturo_payment[0]).val( accounting.formatMoney(Math.round( value * trm) ) )
    })

       

    // $.validator.addMethod("numberCustom", function(value, element){
    //     if (/^(\d{1.3}'(\d{3}')*\d{3}(\.\d{1.3})?|\d{1,3}(\.\d{3})?)$/.test(value)) {
    //         return false;  // FAIL validation when REGEX matches
    //     } else {
    //         return true;   // PASS validation otherwise
    //     };
    // }, "valor no es valido"); 

    $('#payment').validate({
        rules:{
            input_cop:{
                required:'#opt_pay_otr:checked',
                minlength:2,
                // numberCustom: true
            }, 
            input_usd:{
                required:'#opt_pay_otr:checked',
                minlength:2,
                // numberCustom: true
            },
            opt_pay:{
                required:true,
            }
        },
        messages:{
            input_cop:{
                required:'Este campo es obligatorio',
                minlength:'Este valor no es valido',
                //selectnic:'Valor no es valido'
            },
            input_usd:{
                required:'Este campo es obligatorio',
                minlength:'Este valor no es valido'
            },
            opt_pay:{
                required:'Debe seleccionar una opcion de pago valida',
            }
        },
        
        submitHandler: function(form) {
            $('.btn-pse').addClass('color-disabled')
            $('#redirect').removeClass('hidden')
            setTimeout(function(){form.submit();},2000)
            
        }
    });
 })
 

 function disableElements(inputs){
    inputs.forEach(element => {
        element.prop('disabled', true);    
    });
 }

 function formatingElemets(elements){
    elements.forEach(element => {
        element.mask('000,000,000,000,000', {reverse: true});
    });
 }
 function enableElements(inputs){
    inputs.forEach(element => {
        element.prop('disabled', false);    
    });
 }

 function decimalAdjust(type, value, exp) {
    // Si el exp no está definido o es cero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o el exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }