import OkModal from "./components/OkModal.vue";
import { createApp, h } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { vOnClickOutside } from "@vueuse/components";

export default (
    message: string,
    {
        title = undefined,
        forceOk = false,
        size = "md",
    }: { title?: string; forceOk?: boolean; size?: ComponentSize } = {},
) => {
    return new Promise(function (resolve) {
        const newComp = createApp({
            setup() {
                return () =>
                    h(OkModal, {
                        title,
                        message,
                        forceOk,
                        size,
                        onBackdropClicked: () => resolve("backdrop-clicked"),
                        onOkClicked: () => resolve("ok-clicked"),
                        onHidden: () => unmount(),
                    });
            },
        })
            .component("fa-icon", FontAwesomeIcon)
            .directive("on-click-outside", vOnClickOutside);

        /**
         * Mount and show the new OK Modal
         */
        const wrapper = document.createElement("div");
        newComp.mount(wrapper);
        document.body.appendChild(wrapper);

        /**
         * Remove and destroy the modal component
         */
        const unmount = () => {
            newComp.unmount();
            wrapper.remove();
        };
    });
};
