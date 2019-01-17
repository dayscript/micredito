
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('pse-beneficiary-credit-desciption', require('./components/pse/beneficiary/CreditDescription.vue'));
Vue.component('pse-beneficiary-payment', require('./components/pse/beneficiary/payment.vue'));



const app = new Vue({
    el: '#app'
});


$('#myTab a').on('click', function (e) {
    e.preventDefault()
    $(this).tab('show')
})