/*
*   Axios Setup
*/
require('./bootstrap');

/*
*   Vue and base libraries
*/
import Vue                 from 'vue';
import { Link }            from '@inertiajs/inertia-vue';
import BootstrapVue        from 'bootstrap-vue';
import VueMeta             from 'vue-meta';
import { App, plugin }     from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress';
import upperFirst          from 'lodash/upperFirst';
import camelCase           from 'lodash/camelCase';

/*
*   Vee-Validate and all validation rules
*/
require('./validation.js');

/*
*   TinyMCE
*/
require('./tinyMce.js');

/*
*   Third party Components
*/
import VueGoodTablePlugin   from 'vue-good-table';
import draggable            from 'vuedraggable';
import Editor               from '@tinymce/tinymce-vue';
import vueFilterPrettyBytes from 'vue-filter-pretty-bytes';

Vue.use(VueGoodTablePlugin);
Vue.use(vueFilterPrettyBytes);
Vue.component('InertiaLink', Link);
Vue.component('draggable',   draggable);
Vue.component('editor',      Editor);

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


const apps = require.context('../../Modules', true, /register.js$/i)
apps.keys().map(key => {
    const name = key.replace('./','').replace('register.js','');
    // require
    require('../../Modules/'+name+'register.js');
})





/*
*   Initialize App
*/
const el = document.getElementById('app');
InertiaProgress.init();
Vue.prototype.eventHub = new Vue();

new Vue({
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),

            resolveComponent: (name) => {
                //  Determine if this is a primary page, or module page
                let parts  = name.split('::');
                let module = false;
                let page   = false;
                if(parts.length > 1)
                {
                    //  If it is a module page, load the module from the correct folder
                    module = parts[0];
                    page   = parts[1];

                    if(module)
                    {
                        return import(`../../Modules/${module}/Resources/js/Pages/${page}.vue`)
                            .then(({ default: page }) =>
                            {
                                if (page.layout === undefined)
                                {
                                    page.layout = Guest;
                                }
                                return page;
                            });
                    }
                }

                //  primary page, load from the Pages folder
                return import(`./Pages/${name}`)
                    .then(({ default: page }) =>
                    {
                        if (page.layout === undefined)
                        {
                            page.layout = Guest;
                        }
                        return page;
                    });
            }
        },
    }),
}).$mount(el)
