import { Popover } from "bootstrap";

export const vPopoverDirective = {
    mounted: (el: HTMLElement) => {
        new Popover(el);
    },
};
