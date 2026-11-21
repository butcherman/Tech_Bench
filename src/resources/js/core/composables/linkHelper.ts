import { router } from "@inertiajs/vue3";

export const useLinkHelper = (event: MouseEvent, link: LinkHelper): void => {
    // Let the browser handle new-tab gestures
    if (
        event.button === 1 ||
        event.ctrlKey ||
        event.metaKey ||
        event.shiftKey
    ) {
        window.open(link.href, "_blank");
        return;
    }

    // External links use normal navigation
    if (link.external) {
        window.location.href = link.href;
        return;
    }

    // Internal links use Inertia Routing
    router.visit(link.href);
};
