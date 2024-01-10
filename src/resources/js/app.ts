/**
 * Stylesheets
 */
import "../scss/app.scss";

/*
 *   Vue and base libraries
 */
import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

/**
 * Custom Directives
 */
// import { vFocusDirective } from "./Directives/FocusDirective";
// import { vTooltipDirective } from "./Directives/TooltipDirective";
// import { vPopoverDirective } from "./Directives/Popover";

/**
 * Font Awesome Icon Library
 */
// import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
// import "./Modules/FontAwesome.module";

/*
 *   Initialize App
 */
const appName: string = "Tech Bench";

createInertiaApp({
    title: (title: string): string =>
        title ? `${title} - ${appName}` : `${appName}`,
    resolve: (name: string) =>
        resolvePageComponent<any>(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    progress: {
        color: "#4B5563",
    },
    setup({ el, App, props, plugin }) {
        const inertiaApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link) //  Inertial Link
            .component("Head", Head); //  Head title
        // .component("fa-icon", FontAwesomeIcon); //  Font Awesome
        // .directive("focus", vFocusDirective)
        // .directive("tooltip", vTooltipDirective)
        // .directive("popover", vPopoverDirective);

        // inertiaApp.config.globalProperties.$route = route;

        inertiaApp.mount(el);
    },
});

// Prevent Bootstrap dialog from blocking focusin
//  FIXME - Is this necessary?
// document.addEventListener("focusin", (e) => {
//     if (
//         e.target.closest(
//             ".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root"
//         ) !== null
//     ) {
//         e.stopImmediatePropagation();
//     }
// });
