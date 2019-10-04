
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('jquery-validation');
require('jquery-mask-plugin');
require('./payment')
require('./payment-confirmation');

// var accounting = require('accounting');
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
// Vue.component('pse-beneficiary-credit-desciption', require('./components/pse/beneficiary/CreditDescription.vue'));
// Vue.component('pse-beneficiary-payment', require('./components/pse/beneficiary/payment.vue'));

 

const app = new Vue({
    el: '#app'
});


$('#myTab a').on('click', function (e) {
    e.preventDefault()
    $(this).tab('show')
})




// $(document).ready(function(){

//     var colfuturo_payment = [
//         $('#'+'input_cop'),
//         $('#'+'input_usd'),
//     ]

//     var colfuturo_option_one = $('#opt_pay_col')
//     var colfuturo_option_two = $('#opt_pay_otr')

    

//     disableElements(colfuturo_payment)

//     formatingElemets(colfuturo_payment)

//     colfuturo_option_one.change(colfuturo_payment,function(){
//         if( $(this)[0].checked ){
//             disableElements(colfuturo_payment)
//         }
//     })

//     colfuturo_option_two.change(colfuturo_payment,function(){
//         if( $(this)[0].checked ){
//             enableElements(colfuturo_payment)
//         }
//     })

//     colfuturo_payment[0].keyup(colfuturo_payment,function(){
        
//         var trm = $('#trm').val().replace(',','');
//         var value = $(this).val().replace(/\./g,'');
//         $(colfuturo_payment[1]).val( accounting.formatMoney(Math.round10( (value / trm), -2) ) )
        
//     })

//     colfuturo_payment[1].keyup(colfuturo_payment,function(){
//         var trm = $('#trm').val().replace(',','');
//         var value = $(this).val().replace(/\./g,'');
//         $(colfuturo_payment[0]).val( accounting.formatMoney(Math.round( value * trm) ) )
//     })

       

//     // $.validator.addMethod("numberCustom", function(value, element){
//     //     if (/^(\d{1.3}'(\d{3}')*\d{3}(\.\d{1.3})?|\d{1,3}(\.\d{3})?)$/.test(value)) {
//     //         return false;  // FAIL validation when REGEX matches
//     //     } else {
//     //         return true;   // PASS validation otherwise
//     //     };
//     // }, "valor no es valido"); 

//     $('#payment').validate({
//         rules:{
//             input_cop:{
//                 required:'#opt_pay_otr:checked',
//                 minlength:2,
//                 // numberCustom: true
//             }, 
//             input_usd:{
//                 required:'#opt_pay_otr:checked',
//                 minlength:2,
//                 // numberCustom: true
//             },
//         },
//         messages:{
//             input_cop:{
//                 required:'Este campo es obligatorio',
//                 minlength:'Este valor no es valido',
//                 //selectnic:'Valor no es valido'
//             },
//             input_usd:{
//                 required:'Este campo es obligatorio',
//                 minlength:'Este valor no es valido'
//             }
//         },

//         submitHandler: function(form) {
//             form.submit();
//         }
//     });
//  })
 

//  function disableElements(inputs){
//     inputs.forEach(element => {
//         element.prop('disabled', true);    
//     });
//  }

//  function formatingElemets(elements){
//     elements.forEach(element => {
//         element.mask('000.000.000.000.000', {reverse: true});
//     });
//  }
//  function enableElements(inputs){
//     inputs.forEach(element => {
//         element.prop('disabled', false);    
//     });
//  }

//  function decimalAdjust(type, value, exp) {
//     // Si el exp no está definido o es cero...
//     if (typeof exp === 'undefined' || +exp === 0) {
//       return Math[type](value);
//     }
//     value = +value; 
//     exp = +exp;
//     // Si el valor no es un número o el exp no es un entero...
//     if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
//       return NaN;
//     }
//     // Shift
//     value = value.toString().split('e');
//     value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
//     // Shift back
//     value = value.toString().split('e');
//     return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
//   }

//   if (!Math.round10) {
//     Math.round10 = function(value, exp) {
//       return decimalAdjust('round', value, exp);
//     };
//   }

