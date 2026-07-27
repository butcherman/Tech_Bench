import VerifyModal from "../components/modals/VerifyModal.vue";
import { createApp, h } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { vOnClickOutside } from "@vueuse/components";

export default (
    message: string = "Are You Sure",
    title: string = "Please Verify",
) => {
    return new Promise(function (resolve) {
        const newComp = createApp({
            setup() {
                return () =>
                    h(VerifyModal, {
                        title: title,
                        message: message,
                        onYesClicked: () => resolve(true),
                        onNoClicked: () => resolve(false),
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
