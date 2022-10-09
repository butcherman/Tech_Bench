import { Tooltip } from "bootstrap";

export const vTooltipDirective = {
    mounted: (el) => {
        new Tooltip(el);
    }
}
