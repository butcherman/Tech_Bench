import { createInertiaApp, Link } from "@inertiajs/vue3";
import { vOnClickOutside } from "@vueuse/components";
import { copy } from "./core/directives/copyDirective";
import { focus } from "./core/directives/focusDirective";
import { tabTrap } from "./core/directives/tabTrapDirective";
import { tooltip } from "./core/directives/tooltipDirective";

/*
|-------------------------------------------------------------------------------
| CSS Style Sheets
|-------------------------------------------------------------------------------
*/
import "../css/app.css";

/*
|-------------------------------------------------------------------------------
| Font Awesome
|-------------------------------------------------------------------------------
*/
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";

library.add(fas);
library.add(far);

createInertiaApp({
    withApp(app) {
        app.component("fa-icon", FontAwesomeIcon)
            .component("Link", Link)
            .directive("on-click-outside", vOnClickOutside)
            .directive("tooltip", tooltip)
            .directive("copy", copy)
            .directive("focus", focus)
            .directive("tab-trap", tabTrap);
    },
});
