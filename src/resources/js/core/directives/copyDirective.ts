import CopyBadge from "../components/badges/CopyBadge.vue";
import { createApp, h, ref, withDirectives } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { tooltip } from "./tooltipDirective.js";
import type { Directive, Ref } from "vue";

interface CopyTextElement extends HTMLSpanElement {
    _copyValue?: Ref<string>;
    _cleanup?: () => void;
}

export const copy: Directive<CopyTextElement, string> = {
    mounted(el, binding) {
        const variant = ref<VariantType>("warning");
        const tooltipText = ref<string>("Copy to Clipboard");
        const copyValue = ref<string>(binding.value);

        let timeout: number | undefined;

        /**
         * Copy text to clipboard
         */
        const copyText = async () => {
            clearTimeout(timeout);

            try {
                await navigator.clipboard.writeText(copyValue.value);

                variant.value = "success";
                tooltipText.value = "Copied!";
            } catch {
                variant.value = "danger";
                tooltipText.value = "Copy Failed!";
            }

            timeout = setTimeout(() => {
                variant.value = "warning";
                tooltipText.value = "Copy to Clipboard";
            }, 5000);
        };

        /**
         * Create the Copy badge and add to DOM
         */
        const copyTextApp = createApp({
            setup() {
                return () =>
                    withDirectives(
                        h(CopyBadge, {
                            pointer: true,
                            variant: variant.value,
                            onClick: copyText,
                        }),
                        [[tooltip, tooltipText.value]],
                    );
            },
        })
            .component("fa-icon", FontAwesomeIcon)
            .directive("tooltip", tooltip);

        /**
         * Mount and show the copy badge
         */
        const wrapper: CopyTextElement = document.createElement("span");
        wrapper.classList.add("ms-1");

        /**
         * Cleanup and remove from DOM
         */
        el._cleanup = () => {
            clearTimeout(timeout);
            copyTextApp.unmount();
            wrapper.remove();
        };

        el._copyValue = copyValue;

        el.insertAdjacentElement("afterend", wrapper);
        copyTextApp.mount(wrapper);
    },

    updated(el, binding) {
        el._copyValue!.value = binding.value;
    },

    unmounted(el) {
        el._cleanup?.();
    },
};
