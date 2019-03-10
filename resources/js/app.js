/*
*   Vue, Bootstrap and third party libraries
*/
window.Vue = require('vue');
window.axios = require('axios');
window.BootstrapVue = require('bootstrap-vue');
import ClickConfirm from 'click-confirm'

/*
*   Third party components
*/
Vue.component('clickConfirm', ClickConfirm);

/*
*   File Link Components
*/
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('list-file-links', require('./components/ListFileLinks.vue').default);






/*
*   Initialize app
*/
window.onload = function() {
    const app = new Vue({
        el: '#app'
    });
}
