/*
*   Non Vue Javascript for Template
*/
window.expandNav =  function()
{
    var el = document.getElementById('sidebar');
    el.classList.toggle('active');
}

window.expandProfile = function()
{
    console.log('clicke');
    var el = document.getElementById('profileDropdownDiv');
    el.classList.toggle('show');
    return false;
}

/*
*   Vue, Bootstrap and third party libraries
*/
window.Vue              = require('vue');
window.axios            = require('axios');
//window.BootstrapVue     = require('bootstrap-vue');
//window.moment           = require('moment');


/*
*   Include CSRF toden in all axios headers
*/
//window.axios.defaults.headers.common = {
//    'X-Requested-With': 'XMLHttpRequest',
//    'X-CSRF-TOKEN': window.techBench.csrfToken
//}

/*
*   Initialize app
*/
//window.onload = function() {
//    const app = new Vue({
//        el: '#app'
//    });
//}
