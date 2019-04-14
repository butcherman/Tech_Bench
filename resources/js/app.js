/*
*   Vue, Bootstrap and third party libraries
*/
window.Vue              = require('vue');
window.axios            = require('axios');
window.BootstrapVue     = require('bootstrap-vue');
import ClickConfirm       from 'click-confirm';
import vue2Dropzone       from 'vue2-dropzone';
import VueClipboard       from 'vue-clipboard2';
import Editor             from '@tinymce/tinymce-vue';
import VueGoodTablePlugin from 'vue-good-table';
import vSelect            from 'vue-select';
import draggable          from 'vuedraggable';
import VeeValidate        from 'vee-validate';

/*
*   The TinyMCE library
*/
require('tinymce');
require('tinymce/themes/silver');
require('tinymce/themes/mobile');
require('tinymce/plugins/autolink');
require('tinymce/plugins/table');

/*
*   Third party components
*/
Vue.use(VeeValidate);
Vue.use(VueClipboard);
Vue.use(VueGoodTablePlugin);
Vue.component('clickConfirm', ClickConfirm);
Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('editor',       require('@tinymce/tinymce-vue').default);
Vue.component('v-select',     vSelect)
Vue.component('draggable',    draggable);

/*
*   General Components
*/
Vue.component('navbar',        require('./components/NavBar.vue').default);
Vue.component('go-back',       require('./components/GoBack.vue').default);
Vue.component('download-all',  require('./components/DownloadAll.vue').default);
Vue.component('notifications', require('./components/Notifications.vue').default);

/*
*   File Link Components
*/
Vue.component('list-file-links',   require('./components/fileLinks/ListFileLinks.vue').default);
Vue.component('new-file-link',     require('./components/fileLinks/NewFileLink.vue').default);
Vue.component('link-details',      require('./components/fileLinks/LinkDetails.vue').default);
Vue.component('link-instructions', require('./components/fileLinks/LinkInstructions.vue').default);
Vue.component('link-files',        require('./components/fileLinks/LinkFiles.vue').default);
Vue.component('user-upload',       require('./components/fileLinks/UserFileUpload.vue').default);

/*
*   Systems components
*/
Vue.component('system-files', require('./components/system/SystemFiles.vue').default);

/*
*   Admin Components
*/
Vue.component('user-list',        require('./components/admin/userList.vue').default);
Vue.component('admin-file-links', require('./components/admin/fileLinks.vue').default);

/*
*   Installer Components
*/
Vue.component('logo-replace',     require('./components/installer/logoReplace.vue').default);
Vue.component('test-email',       require('./components/installer/testEmail.vue').default);
Vue.component('new-system-form',  require('./components/installer/newSystem.vue').default);
Vue.component('edit-system-form', require('./components/installer/editSystem.vue').default);

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
window.onload = function() {
    const app = new Vue({
        el: '#app'
    });
}
