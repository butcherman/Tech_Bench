/*
*   Vue, Bootstrap and third party libraries
*/
window.Vue = require('vue');
window.axios = require('axios');
window.BootstrapVue = require('bootstrap-vue');
import ClickConfirm from 'click-confirm';
import vue2Dropzone from 'vue2-dropzone';
import VueClipboard from 'vue-clipboard2';
import Editor from '@tinymce/tinymce-vue';

/*
*   The TinyMCE library
*/
require('tinymce');
require('tinymce/themes/silver');
require('tinymce/themes/mobile');
require('tinymce/plugins/autolink');
require('tinymce/plugins/table');

/*
*   Include CSRF toden in all axios headers
*/
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN' : window.techBench.csrfToken
}

/*
*   Third party components
*/
Vue.use(VueClipboard);
Vue.component('clickConfirm', ClickConfirm);
Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('editor', require('@tinymce/tinymce-vue').default);

/*
*   File Link Components
*/
Vue.component('list-file-links',   require('./components/fileLinks/ListFileLinks.vue').default);
Vue.component('new-file-link',     require('./components/fileLinks/NewFileLink.vue').default);
Vue.component('link-details',      require('./components/fileLinks/LinkDetails.vue').default);
Vue.component('link-instructions', require('./components/fileLinks/LinkInstructions.vue').default);
Vue.component('link-files',        require('./components/fileLinks/LinkFiles.vue').default);

/*
*   Initialize app
*/
window.onload = function() {
    const app = new Vue({
        el: '#app'
    });
}