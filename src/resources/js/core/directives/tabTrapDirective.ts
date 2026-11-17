import type { Directive } from "vue";

const focusableSelector = [
    "input:not([disabled]):not([type='hidden'])",
    "select:not([disabled])",
    "textarea:not([disabled])",
    "button:not([disabled])",
    "a[href]",
    "[tabindex]:not([tabindex='-1'])",
].join(",");

export const tabTrap: Directive<HTMLElement> = {
    mounted(el) {
        const handleKeydown = (event: KeyboardEvent) => {
            if (event.key !== "Tab") {
                return;
            }

            const focusable = Array.from(
                el.querySelectorAll<HTMLElement>(focusableSelector),
            ).filter((element) => {
                return element.offsetParent !== null;
            });

            if (focusable.length === 0) {
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey) {
                // Shift + Tab from first → last
                if (document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                }
            } else {
                // Tab from last → first
                if (document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        };

        el.addEventListener("keydown", handleKeydown);

        // Store the handler so it can be removed later.
        (
            el as HTMLElement & { _tabTrapHandler?: typeof handleKeydown }
        )._tabTrapHandler = handleKeydown;
    },

    unmounted(el) {
        const element = el as HTMLElement & {
            _tabTrapHandler?: (event: KeyboardEvent) => void;
        };

        if (element._tabTrapHandler) {
            el.removeEventListener("keydown", element._tabTrapHandler);
        }
    },
};
