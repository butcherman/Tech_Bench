import { Popover } from "bootstrap";

export const vPopoverDirective = {
    mounted: (el) => {
        new Popover(el);
    }
}
