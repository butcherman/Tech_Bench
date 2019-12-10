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
import Vue                  from 'vue';
import BootstrapVue         from 'bootstrap-vue';
import VueGoodTablePlugin   from 'vue-good-table';
import route                from 'ziggy';
import { Ziggy }            from '../assets/js/ziggy';
import vue2Dropzone         from 'vue2-dropzone';
import VuePhoneNumberInput  from 'vue-phone-number-input';
import Multiselect          from 'vue-multiselect';
import vSelect              from 'vue-select';
import draggable            from 'vuedraggable';
import vueFilterPrettyBytes from 'vue-filter-pretty-bytes';

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

Vue.component('vue-dropzone', vue2Dropzone);
Vue.component('editor', require('@tinymce/tinymce-vue').default);
Vue.component('vue-phone-number-input', VuePhoneNumberInput);
Vue.component('multiselect', Multiselect);
Vue.component('v-select', vSelect);
Vue.component('draggable',    draggable);

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
Vue.component('go-back', require('./components/GoBack.vue').default);

/*
*   Dashboard and Template Components
*/
Vue.component('notification-dashboard', require('./components/NotificationDashboard.vue').default);

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
Vue.component('customer-files',    require('./components/customer/customerFiles.vue').default);

/*
*   Vue Tech Tip Components
*/
Vue.component('search-tips',       require('./components/tips/searchTips.vue').default);
Vue.component('new-tip-form',      require('./components/tips/newTipForm.vue').default);
Vue.component('edit-tip-form',     require('./components/tips/editTipForm.vue').default);
Vue.component('tech-tip-details',  require('./components/tips/tipDetails.vue').default);
Vue.component('tech-tip-files',    require('./components/tips/tipFiles.vue').default);
Vue.component('tech-tip-comments', require('./components/tips/tipComments.vue').default);

/*
*   Administration Components
*/
Vue.component('user-form',        require('./components/admin/userForm.vue').default);
Vue.component('user-list',        require('./components/admin/userList.vue').default);
Vue.component('user-roles',       require('./components/admin/userRoles.vue').default);
Vue.component('user-deleted',     require('./components/admin/userDeleted.vue').default);
Vue.component('admin-file-links', require('./components/admin/fileLinks.vue').default);

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
