/*
*   Axios Setup
*/
require('./bootstrap');

/*
*   Vue and base libraries
*/
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueMeta from 'vue-meta';
import { App, plugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';
import upperFirst from 'lodash/upperFirst';
import camelCase from 'lodash/camelCase';

/*
*   Vee-Validate and all validation rules
*/
require('./validation.js');

/*
*   Default Layout
*/
import Guest from './Layouts/guest';

/*
*   Plugins
*/
Vue.use(plugin);
Vue.use(VueMeta, { refreshOnceOnNavigation: true });
Vue.use(BootstrapVue);
Vue.mixin({ methods: { route }});

/*
*   Globally Register all Base Components in the Components/Base Folder
*/
const requireComponent = require.context('./Components/Base', true, /[A-Z]\w+\.(vue|js)$/);

  requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName)
    const componentName   = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

    // Register component globally
    Vue.component( componentName, componentConfig.default || componentConfig);
  });

/*
*   Initialize App
*/
const el = document.getElementById('app');
InertiaProgress.init();

new Vue({
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => import(`./Pages/${name}`)
        .then(({ default: page }) => {
            if (page.layout === undefined) {
                page.layout = Guest;
            }
            return page;
        }),
    },
  }),
}).$mount(el)
