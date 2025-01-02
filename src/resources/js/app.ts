/*
|-------------------------------------------------------------------------------
| CSS Style Sheets
|-------------------------------------------------------------------------------
*/
import "../css/app.css";

/*
|-------------------------------------------------------------------------------
| Vue and base libraries
|-------------------------------------------------------------------------------
*/
import { createApp, DefineComponent, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import PrimeVue from "primevue/config";
import { createPinia } from "pinia";
import Aura from "@primevue/themes/aura";
// import "./echo";

/*
|-------------------------------------------------------------------------------
| Font Awesome
|-------------------------------------------------------------------------------
*/
// import { aliases, fa } from "vuetify/iconsets/fa-svg";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";

library.add(fas);
library.add(far);

/*
|-------------------------------------------------------------------------------
| App Constants
|-------------------------------------------------------------------------------
*/
const appName = "Tech Bench";
const pinia = createPinia();

/*
|-------------------------------------------------------------------------------
| PrimeVue Configuration
|-------------------------------------------------------------------------------
*/
const primeVueConfig = {
    theme: {
        preset: Aura,
        options: {
            cssLayer: {
                name: "primevue",
                order: "tailwind-base, primevue, tailwind-utilities",
            },
        },
    },
};

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
            .use(PrimeVue, primeVueConfig)
            .component("fa-icon", FontAwesomeIcon)
            .component("Link", Link)
            .component("Head", Head);

        inertiaApp.config.globalProperties.$route = route;

        inertiaApp.mount(el);
    },
    title: (title: string) => (title ? `${title} - ${appName}` : `${appName}`),
});
