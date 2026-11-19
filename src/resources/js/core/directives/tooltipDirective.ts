import type { Directive } from "vue";

const placements: Placement[] = ["top", "bottom", "left", "right"];

export const tooltip: Directive<TooltipElement, BindingValue> = {
    mounted(el, binding) {
        const placement: Placement =
            placements.find((p) => binding.modifiers[p]) ?? "top";
        const options = normalize(binding.value, placement);

        const tooltip = document.createElement("div");
        tooltip.className = `tb-tooltip tb-tooltip-${options.placement}`;
        tooltip.textContent = options.text;

        document.body.appendChild(tooltip);

        tooltip.style.position = "fixed";
        tooltip.style.opacity = "0";
        tooltip.style.pointerEvents = "none";

        const show = () => {
            positionTooltip(el, tooltip, options.placement);

            tooltip.style.opacity = "1";
        };

        const hide = () => {
            tooltip.style.opacity = "0";
        };

        const enter = () => {
            el._tooltipTimeout = window.setTimeout(show, options.delay);
        };

        const leave = () => {
            clearTimeout(el._tooltipTimeout);

            hide();
        };

        el.addEventListener("mouseenter", enter);
        el.addEventListener("mouseleave", leave);
        el.addEventListener("focus", enter);
        el.addEventListener("blur", leave);

        el._tooltip = tooltip;

        el._cleanup = () => {
            clearTimeout(el._tooltipTimeout);

            tooltip.remove();

            el.removeEventListener("mouseenter", enter);
            el.removeEventListener("mouseleave", leave);
            el.removeEventListener("focus", enter);
            el.removeEventListener("blur", leave);
        };
    },

    updated(el, binding) {
        if (!el._tooltip) return;

        const placement: Placement =
            placements.find((p) => binding.modifiers[p]) ?? "top";
        const options = normalize(binding.value, placement);

        el._tooltip.textContent = options.text;
        el._tooltip.className = `tb-tooltip tb-tooltip-${options.placement}`;
    },

    unmounted(el) {
        el._cleanup?.();
    },
};

function normalize(
    value: BindingValue,
    placement: Placement,
): Required<TooltipOptions> {
    if (typeof value === "string") {
        return {
            text: value,
            placement: placement,
            delay: 250,
        };
    }

    return {
        text: value.text,
        placement: value.placement ?? "top",
        delay: value.delay ?? 250,
    };
}

function positionTooltip(
    target: HTMLElement,
    tooltip: HTMLElement,
    placement: Placement,
) {
    const rect = target.getBoundingClientRect();
    const tip = tooltip.getBoundingClientRect();

    const gap = 8;

    let top = 0;
    let left = 0;

    switch (placement) {
        case "top":
            top = rect.top - tip.height - gap;
            left = rect.left + (rect.width - tip.width) / 2;
            break;

        case "bottom":
            top = rect.bottom + gap;
            left = rect.left + (rect.width - tip.width) / 2;
            break;

        case "left":
            top = rect.top + (rect.height - tip.height) / 2;
            left = rect.left - tip.width - gap;
            break;

        case "right":
            top = rect.top + (rect.height - tip.height) / 2;
            left = rect.right + gap;
            break;
    }

    tooltip.style.top = `${top}px`;
    tooltip.style.left = `${left}px`;
}
