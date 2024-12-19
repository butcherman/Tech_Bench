/*
|-------------------------------------------------------------------------------
| CSS Style Sheets
|-------------------------------------------------------------------------------
*/
import "../css/app.css";
import "vuetify/styles";

/*
|-------------------------------------------------------------------------------
| Vue and base libraries
|-------------------------------------------------------------------------------
*/
import { createApp, DefineComponent, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { createVuetify } from "vuetify";
import { createPinia } from "pinia";

/*
|-------------------------------------------------------------------------------
| App Constants
|-------------------------------------------------------------------------------
*/
const appName = "Tech Bench";
const vuetify = createVuetify();
const pinia = createPinia();

/*
|-------------------------------------------------------------------------------
| Initialize App
|-------------------------------------------------------------------------------
*/
createInertiaApp({
    resolve: (name: string): Promise<DefineComponent> => {
        const pages = import.meta.glob("./Pages/**/*.vue");
        return pages[`./Pages/${name}.vue`]() as Promise<DefineComponent>;
    },
    setup({ el, App, props, plugin }) {
        const inertiaApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(vuetify)
            .component("Link", Link)
            .component("Head", Head);

        inertiaApp.config.globalProperties.$route = route;

        inertiaApp.mount(el);
    },
    title: (title: string) => (title ? `${title} - ${appName}` : `${appName}`),
});
