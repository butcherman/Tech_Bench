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
import { FontAwesomeIcon }              from '@fortawesome/vue-fontawesome';
//  Custom Directives
import { vFocusDirective }              from './Directives/Focus';
import { vTooltipDirective }            from './Directives/Tooltip';

/**
 * Font Awesome Icon Library
 */
import './Modules/fontAwesome.module';

/*
*   Initialize App
*/
const appName = 'Tech Bench';

createInertiaApp({
    title  : (title)=> `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent<any>(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const inertiaApp = createApp({ render: () => h(app, props) })
            .use(plugin)
            .component('Link', Link)                            //  Inertial Link
            .component('Head', Head)                            //  Head title
            .component('fa-icon', FontAwesomeIcon)              //  Font Awesome
            .directive('focus', vFocusDirective)
            .directive('tooltip', vTooltipDirective)

        inertiaApp.config.globalProperties.route = window.route;      //  Ziggy Route Provider
        inertiaApp.mount(el);
      },
});

InertiaProgress.init({ color: '#4B5563' });
