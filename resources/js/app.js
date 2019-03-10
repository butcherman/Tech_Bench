/*
*   Vue, Bootstrap and third party libraries
*/
window.Vue = require('vue');
window.axios = require('axios');
window.BootstrapVue = require('bootstrap-vue');
import ClickConfirm from 'click-confirm';
import vue2Dropzone from 'vue2-dropzone';

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
Vue.component('clickConfirm', ClickConfirm);
Vue.component('vue-dropzone', vue2Dropzone);

/*
*   File Link Components
*/
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('list-file-links', require('./components/ListFileLinks.vue').default);


Vue.component('new-file-link', require('./components/NewFileLink.vue').default);
//Vue.component('multi-file-drop', require('./components/MutliFileDrop.vue').default);

/*
*   Initialize app
*/
window.onload = function() {
    const app = new Vue({
        el: '#app'
    });
}
