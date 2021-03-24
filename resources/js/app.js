require('./bootstrap');
import Vue from 'vue';
import { App, plugin } from '@inertiajs/inertia-vue';
import VueMeta from 'vue-meta';
import BootstrapVue from 'bootstrap-vue';
import { InertiaProgress } from '@inertiajs/progress';

import Layout from './Layouts/app';
import Guest from './Layouts/guest';

Vue.use(plugin);
Vue.use(VueMeta, { refreshOnceOnNavigation: true });
Vue.use(BootstrapVue);

Vue.mixin({ methods: { route }});

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
