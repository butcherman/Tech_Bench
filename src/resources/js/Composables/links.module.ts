import { router } from "@inertiajs/vue3";

/*
|---------------------------------------------------------------------------
| Handle an event click for a link.  If Click+Ctrl, open in new tab.
|---------------------------------------------------------------------------
*/
export const handleLinkClick = (
    event: MouseEvent,
    url?: string,
    method?: "get" | "put" | "post" | "delete"
) => {
    if (url) {
        if (event.ctrlKey) {
            window.open(url);
        } else {
            router.visit(url, {
                method: method ?? "get",
            });
        }
    }
};
