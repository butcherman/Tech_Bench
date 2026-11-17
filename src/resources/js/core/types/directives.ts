interface TooltipOptions {
    text: string;
    placement?: Placement;
    delay?: number;
}

type BindingValue = string | TooltipOptions;

interface TooltipElement extends HTMLElement {
    _tooltip?: HTMLElement;
    _tooltipTimeout?: number;
    _cleanup?: () => void;
}
