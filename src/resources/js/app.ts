import { createInertiaApp, Link } from "@inertiajs/vue3";
import { vOnClickOutside } from "@vueuse/components";

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
            .directive("click-outside", vOnClickOutside);
    },
});
