/**
 * Stylesheets
 */
import "../css/app.css";

/*
 *   Vue and base libraries
 */
import { createApp, DefineComponent, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";

const appName = "Tech Bench";

createInertiaApp({
    resolve: (name: string): Promise<DefineComponent> => {
        const pages = import.meta.glob("./Pages/**/*.vue");
        return pages[`./Pages/${name}.vue`]() as Promise<DefineComponent>;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
    title: (title: string) => (title ? `${title} - ${appName}` : `${appName}`),
});
