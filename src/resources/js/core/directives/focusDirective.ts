import type { Directive } from "vue";

export const focus: Directive<HTMLElement> = {
    mounted(el) {
        if (
            el.matches(
                "input, textarea, select, button, [tabindex]:not([tabindex='-1'])",
            )
        ) {
            el.focus();
            return;
        }

        const focusable = el.querySelector<HTMLElement>(
            "input, textarea, select, button, [tabindex]:not([tabindex='-1'])",
        );

        focusable?.focus();
    },
};
