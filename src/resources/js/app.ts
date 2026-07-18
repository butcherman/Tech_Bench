import { createInertiaApp, Head, Link } from "@inertiajs/vue3";

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
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";

library.add(fas);
library.add(far);

createInertiaApp({
    withApp(app) {
        app.component("fa-icon", FontAwesomeIcon)
            .component("Link", Link)
            .component("Head", Head);
    },
});
