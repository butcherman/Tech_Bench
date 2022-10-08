/*
*   Axios Setup
*/
import './bootstrap';

/**
 * Stylesheets
 */
import '../scss/app.scss';

/*
*   Vue and base libraries
*/
import { createApp, h }                 from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress }              from '@inertiajs/progress';
import { resolvePageComponent }         from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue }                     from '../../vendor/tightenco/ziggy/dist/vue.m';
// import { createPinia }                  from 'pinia';
// import Vue                 from 'vue';
// import { Link }            from '@inertiajs/inertia-vue';
// import BootstrapVue        from 'bootstrap-vue';
// import VueMeta             from 'vue-meta';
// import { App, plugin }     from '@inertiajs/inertia-vue';
// import { InertiaProgress } from '@inertiajs/progress';
// import upperFirst          from 'lodash/upperFirst';
// import camelCase           from 'lodash/camelCase';
// import { createPinia, PiniaVuePlugin } from 'pinia';
// import VueCompositionApi from '@vue/composition-api';

/*
*   Vee-Validate and all validation rules
*/
// require('./validation.js');

/*
*   TinyMCE
*/
// require('./tinyMce.js');

/*
*   Third party Components
*/
// import VueGoodTablePlugin   from 'vue-good-table';
// import draggable            from 'vuedraggable';
// import Editor               from '@tinymce/tinymce-vue';
// import vueFilterPrettyBytes from 'vue-filter-pretty-bytes';

// Vue.use(VueGoodTablePlugin);
// Vue.use(vueFilterPrettyBytes);
// Vue.component('InertiaLink', Link);
// Vue.component('draggable',   draggable);
// Vue.component('editor',      Editor);

/*
*   Default Layout
*/
// import Guest from './Layouts/guest';

/*
*   Plugins
*/
// Vue.use(plugin);
// Vue.use(VueMeta, { refreshOnceOnNavigation: true });
// Vue.use(BootstrapVue);
// Vue.mixin({ methods: { route }});
// Vue.use(PiniaVuePlugin);
// Vue.use(VueCompositionApi);

/*
*   Globally Register all Base Components in the Components/Base Folder
*/
// const requireComponent = require.context('./Components/Base', true, /[A-Z]\w+\.(vue|js)$/);
// requireComponent.keys().forEach(fileName => {
//     const componentConfig = requireComponent(fileName);
//     const componentName   = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

//     // Register component globally
//     Vue.component( componentName, componentConfig.default || componentConfig);
// });

/**
 * Globally Register all Notification Components from the attached Modules
 */
//  const requireNotif = require.context('../../Modules', true, /Base\/Resources\/js\/Notifications\/[A-Z]\w+\/[A-Z]\w+\.vue$/);
//  requireNotif.keys().forEach(fileName => {
//      const componentConfig = requireModule(fileName);
//      const componentName   = upperFirst(camelCase(fileName.split('/').pop().replace(/\.\w+$/, '')));

//      // Register component globally
//      Vue.component( componentName, componentConfig.default || componentConfig);
//  });


/*
*   Initialize App
*/
const appName = 'Tech Bench';

createInertiaApp({
    title: (title)  => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const inertiaApp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue)
            // .use(createPinia())
            .component('Link', Link)                            //  Inertial Link
            .component('Head', Head)                            //  Head title
            // .component('Layout', Layout)                        //  Default Layout
            // .component('font-awesome-icon', FontAwesomeIcon)    //  Font Awesome
            // .component('Modal', Modal)                          //  Pop-up Modal

        /**
         * Additional Global Properties
         */
        // inertiaApp.config.globalProperties.route = route;      //  Ziggy Route Provider
        return inertiaApp.mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
// const el = document.getElementById('app');
// const pinia = createPinia();
// InertiaProgress.init();
// Vue.prototype.eventHub = new Vue();

// new Vue({
//     pinia,
//     render: h => h(App, {
//         props: {
//             initialPage: JSON.parse(el.dataset.page),

//             resolveComponent: (name) => {
//                 //  Determine if this is a primary page, or module page
//                 let parts  = name.split('::');
//                 let module = false;
//                 let page   = false;
//                 if(parts.length > 1)
//                 {
//                     //  If it is a module page, load the module from the correct folder
//                     module = parts[0];
//                     page   = parts[1];

//                     if(module)
//                     {
//                         return import(`../../Modules/${module}/Resources/js/Pages/${page}.vue`)
//                             .then(({ default: page }) =>
//                             {
//                                 if (page.layout === undefined)
//                                 {
//                                     page.layout = Guest;
//                                 }
//                                 return page;
//                             });
//                     }
//                 }

//                 //  primary page, load from the Pages folder
//                 return import(`./Pages/${name}`)
//                     .then(({ default: page }) =>
//                     {
//                         if (page.layout === undefined)
//                         {
//                             page.layout = Guest;
//                         }
//                         return page;
//                     });
//             }
//         },
//     }),
// }).$mount(el)
