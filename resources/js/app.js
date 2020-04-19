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
*   File chunk size for file uploads
*/
window.chunkSize = 500000;

/*
*   File Imports
*/
import Vue                  from 'vue';
import BootstrapVue         from 'bootstrap-vue';
import VueGoodTablePlugin   from 'vue-good-table';
import route                from 'ziggy';
import vue2Dropzone         from 'vue2-dropzone';
import VuePhoneNumberInput  from 'vue-phone-number-input';
import Multiselect          from 'vue-multiselect';
import vSelect              from 'vue-select';
import draggable            from 'vuedraggable';
import vueFilterPrettyBytes from 'vue-filter-pretty-bytes';
import Avatar               from 'vue-avatar'
import VueClipboard         from 'vue-clipboard2';
import { Ziggy }            from '../assets/js/ziggy';
import { AtomSpinner }      from 'epic-spinners';
import {HollowDotsSpinner}  from 'epic-spinners';

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
Vue.use(vueFilterPrettyBytes);
Vue.use(VueClipboard);

/*
*   Third party components
*/
Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('vue-phone-number-input', VuePhoneNumberInput);
Vue.component('multiselect', Multiselect);
Vue.component('v-select', vSelect);
Vue.component('draggable',    draggable);
Vue.component('avatar', Avatar);
Vue.component('atom-spinner', AtomSpinner);
Vue.component('hollow-dots-spinner', HollowDotsSpinner);

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
*   Global Components
*/
Vue.component('go-back',         require('./components/GoBack.vue').default);
Vue.component('file-upload',     require('./components/FileUpload.vue').default);
Vue.component('form-submit',     require('./components/FormSubmit.vue').default);

/*
*   Dashboard and Template Components
*/
Vue.component('notification-dashboard', require('./components/NotificationDashboard.vue').default);

/*
*   User Profile Components
*/
Vue.component('user-profile',  require('./components/user/userProfile.vue').default);
Vue.component('user-settings', require('./components/user/userSettings.vue').default);

/*
*   Vue File Link Components
*/
Vue.component('list-file-links',    require('./components/fileLinks/ListFileLinks.vue').default);
Vue.component('new-file-link-form', require('./components/fileLinks/NewFileLink.vue').default);
Vue.component('link-details',       require('./components/fileLinks/LinkDetails.vue').default);
Vue.component('link-instructions',  require('./components/fileLinks/LinkInstructions.vue').default);
Vue.component('link-files',         require('./components/fileLinks/LinkFiles.vue').default);
Vue.component('guest-download',     require('./components/fileLinks/GuestFileDownloads.vue').default);
Vue.component('guest-upload',       require('./components/fileLinks/GuestFileUpload.vue').default);

/*
*   Vue Customer Components
*/
Vue.component('customer-master',     require('./components/customer/customerDetailsMaster.vue').default);
Vue.component('customer-list',       require('./components/customer/customerList.vue').default);
Vue.component('new-customer-form',   require('./components/customer/newCustomer.vue').default);
Vue.component('customer-details',    require('./components/customer/customerDetails.vue').default);
Vue.component('customer-equipment',  require('./components/customer/customerEquipment.vue').default);
Vue.component('equipment-form',      require('./components/customer/customerEquipmentForm.vue').default);
Vue.component('customer-contacts',   require('./components/customer/customerContacts.vue').default);
Vue.component('customer-notes',      require('./components/customer/CustomerNotes.vue').default);
Vue.component('customer-files',      require('./components/customer/customerFiles.vue').default);
Vue.component('customer-search',     require('./components/customer/customerSearch.vue').default);

/*
*   Vue Tech Tip Components
*/
Vue.component('search-tips',         require('./components/tips/searchTips.vue').default);
Vue.component('new-tip-form',        require('./components/tips/newTipForm.vue').default);
Vue.component('edit-tip-form',       require('./components/tips/editTipForm.vue').default);
Vue.component('tech-tip-details',    require('./components/tips/tipDetails.vue').default);
Vue.component('tech-tip-files',      require('./components/tips/tipFiles.vue').default);
Vue.component('tech-tip-comments',   require('./components/tips/tipComments.vue').default);

/*
*   Administration Components
*/
Vue.component('user-form',           require('./components/admin/userForm.vue').default);
Vue.component('user-list',           require('./components/admin/userList.vue').default);
Vue.component('user-roles',          require('./components/admin/userRoles.vue').default);
Vue.component('user-deleted',        require('./components/admin/userDeleted.vue').default);
Vue.component('admin-file-links',    require('./components/admin/fileLinks.vue').default);
Vue.component('customer-id-modify',  require('./components/customer/customerIdModify.vue').default);
Vue.component('customer-file-types', require('./components/customer/CustomerFileTypes.vue').default);
Vue.component('customers-disabled',  require('./components/customer/DisabledCustomers.vue').default);

/*
*   Installer Components
*/
Vue.component('equipment-categories', require('./components/installer/categories.vue').default);
Vue.component('equipment-list',       require('./components/installer/equipmentList.vue').default);
Vue.component('new-equipment-form',   require('./components/installer/newEquipment.vue').default);
Vue.component('edit-equipment-form',  require('./components/installer/editEquipment.vue').default);
Vue.component('logo-form',            require('./components/installer/logoForm.vue').default);
Vue.component('tb-configuration',     require('./components/installer/configuration.vue').default);
Vue.component('email-settings',       require('./components/installer/emailSettings.vue').default);
Vue.component('logs-doughnut-chart',  require('./components/installer/logsDoughnutChart.vue').default);
Vue.component('delete-log',           require('./components/installer/deleteLog.vue').default);
Vue.component('tb-backups',           require('./components/installer/backups.vue').default);

/*
*   Add On Module Management Components
*/
Vue.component('active-modules', require('./components/modules/active.vue').default);
Vue.component('staged-modules', require('./components/modules/staged.vue').default);
Vue.component('upload-module',  require('./components/modules/upload.vue').default);

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
