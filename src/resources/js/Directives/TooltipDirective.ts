import { Tooltip } from "bootstrap";

export const vTooltipDirective = {
    mounted: (el: HTMLElement) => {
        new Tooltip(el);
    },
    beforeUnmount(el: HTMLElement) {
        const element = Tooltip.getInstance(el);
        element?.disable();
        element?.dispose();
    },
};
