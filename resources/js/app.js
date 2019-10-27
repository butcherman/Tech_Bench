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

/*
*   Vue, Bootstrap and third party libraries
*/
window.axios = require('axios');
Vue.use(VueGoodTablePlugin);
Vue.use(BootstrapVue);

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
Vue.component('list-file-links', require('./components/fileLinks/ListFileLinks.vue').default);
//Vue.component('new-file-link',     require('./components/fileLinks/NewFileLink.vue').default);
//Vue.component('link-details',      require('./components/fileLinks/LinkDetails.vue').default);
//Vue.component('link-instructions', require('./components/fileLinks/LinkInstructions.vue').default);
//Vue.component('link-files',        require('./components/fileLinks/LinkFiles.vue').default);
//Vue.component('user-upload',       require('./components/fileLinks/UserFileUpload.vue').default);


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
