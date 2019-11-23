/*
*   Non Vue Javascript for Template
*/
window.expandNav = function () {
    var el = document.getElementById('sidebar');
    el.classList.toggle('active');
}

window.expandProfile = function () {
    var el = document.getElementById('profileDropdownDiv');
    el.classList.toggle('show');
    return false;
}

/*
*   File Imports
*/
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueGoodTablePlugin from 'vue-good-table';
import route from 'ziggy';
import { Ziggy } from '../assets/js/ziggy';
import vue2Dropzone from 'vue2-dropzone';
import Editor from '@tinymce/tinymce-vue';
import VuePhoneNumberInput from 'vue-phone-number-input'

/*
*   The TinyMCE library
*/
require('tinymce');
require('tinymce/themes/silver');
require('tinymce/themes/mobile');
require('tinymce/plugins/advlist');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
require('tinymce/plugins/table');

/*
*   Vue, Bootstrap and third party libraries
*/
window.axios = require('axios');
Vue.use(VueGoodTablePlugin);
Vue.use(BootstrapVue);

Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('vue-phone-number-input', VuePhoneNumberInput);

Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    },
    data: function () {
        return {
            dashify: require('dashify'),
        }
    }
});

/*
*   Vue File Link Components
*/
Vue.component('list-file-links',    require('./components/fileLinks/ListFileLinks.vue').default);
Vue.component('new-file-link-form', require('./components/fileLinks/NewFileLink.vue').default);
Vue.component('link-details',       require('./components/fileLinks/LinkDetails.vue').default);
Vue.component('link-files',         require('./components/fileLinks/LinkFiles.vue').default);
Vue.component('guest-download',     require('./components/fileLinks/GuestFileDownloads.vue').default);
Vue.component('guest-upload',       require('./components/fileLinks/GuestFileUpload.vue').default);

/*
*   Vue Customer Components
*/
Vue.component('customer-list',     require('./components/customer/customerList.vue').default);
Vue.component('new-customer-form', require('./components/customer/newCustomer.vue').default);
Vue.component('customer-details',  require('./components/customer/customerDetails.vue').default);
Vue.component('customer-systems',  require('./components/customer/customerSystems.vue').default);
Vue.component('customer-contacts', require('./components/customer/customerContacts.vue').default);
Vue.component('customer-notes',    require('./components/customer/CustomerNotes.vue').default);

/*
*   Include CSRF toden in all axios headers
*/
window.axios.defaults.headers.common = {
   'X-Requested-With': 'XMLHttpRequest',
   'X-CSRF-TOKEN': window.techBench.csrfToken
}

/*
*   Initialize app
*/
window.onload = function () {
    const app = new Vue({
        el: '#app'
    });
}
