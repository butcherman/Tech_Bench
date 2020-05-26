/*
*   Non Vue Javascript for Template
*/
window.expandNav = function () {
    var el = document.getElementById('side-nav');
    el.classList.toggle('active');
}

// window.expandProfile = function () {
//     var el = document.getElementById('profileDropdownDiv');
//     el.classList.toggle('show');
//     return false;
// }

/*
*   File chunk size for file uploads
*/
window.chunkSize = 500000;

/*
*   File Imports
*/
import Vue                  from 'vue';
import BootstrapVue         from 'bootstrap-vue';
// import VueGoodTablePlugin   from 'vue-good-table';
// import route                from 'ziggy';
// import vue2Dropzone         from 'vue2-dropzone';
// import VuePhoneNumberInput  from 'vue-phone-number-input';
// import Multiselect          from 'vue-multiselect';
// import vSelect              from 'vue-select';
// import draggable            from 'vuedraggable';
// import vueFilterPrettyBytes from 'vue-filter-pretty-bytes';
// import Avatar               from 'vue-avatar'
// import VueClipboard         from 'vue-clipboard2';
// import { Ziggy }            from '../assets/js/ziggy';
import { AtomSpinner }      from 'epic-spinners';
// import {HollowDotsSpinner}  from 'epic-spinners';

/*
*   The TinyMCE library
*/
// require('tinymce');
// require('tinymce/themes/silver');
// require('tinymce/themes/mobile');
// require('tinymce/plugins/advlist');
// require('tinymce/plugins/autolink');
// require('tinymce/plugins/lists');
// require('tinymce/plugins/link');
// require('tinymce/plugins/image');
// require('tinymce/plugins/table');
// require('tinymce/plugins/fullscreen');
// require('tinymce/plugins/spellchecker');
// require('tinymce/plugins/preview');

/*
*   Vue, Bootstrap and third party libraries
*/
window.axios = require('axios');
// Vue.use(VueGoodTablePlugin);
Vue.use(BootstrapVue);
// Vue.use(vueFilterPrettyBytes);
// Vue.use(VueClipboard);

/*
*   Third party components
*/
// Vue.component('vue-dropzone', vue2Dropzone);
// Vue.component('editor', require('@tinymce/tinymce-vue').default);
// Vue.component('vue-phone-number-input', VuePhoneNumberInput);
// Vue.component('multiselect', Multiselect);
// Vue.component('v-select', vSelect);
// Vue.component('draggable',    draggable);
// Vue.component('avatar', Avatar);
Vue.component('atom-spinner', AtomSpinner);
// Vue.component('hollow-dots-spinner', HollowDotsSpinner);

const eventHub = new Vue();
Vue.mixin({
    methods: {
        route: (name, params, absolute) => route(name, params, absolute, Ziggy),
    },
    data: function () {
        return {
            dashify: require('dashify'),
            eventHub: eventHub,
        }
    }
});

/*
*   Global Components
*/
// Vue.component('go-back',         require('./components/GoBack.vue').default);
// Vue.component('file-upload',     require('./components/FileUpload.vue').default);
Vue.component('form-submit',     require('./components/formSubmit.vue').default);
// Vue.component('bookmark',        require('./components/Bookmark.vue').default);
Vue.component('axios-error', require('./components/errorMessage.vue').default);

/*
*   Individual Page Components
*/
Vue.component('logout', require('./components/auth/logout.vue').default);
Vue.component('dashboard-nofifications', require('./components/auth/dashboardNotifications.vue').default);

/*
*   User Components
*/
Vue.component('user-account', require('./components/auth/accountSettings.vue').default);
Vue.component('user-settings', require('./components/auth/userSettings.vue').default);
Vue.component('change-password', require('./components/auth/changePassword.vue').default);



/*
*   Include CSRF toden in all axios headers
*/
window.axios.defaults.headers.common = {
   'X-Requested-With': 'XMLHttpRequest',
   'X-CSRF-TOKEN':      window.techBench.csrfToken,
   'Content-Type':     'application/json',
}

/*
*   Initialize app
*/
window.onload = function () {
    const app = new Vue({
        el: '#app'
    });
}
