import ErrorModal from "./ErrorModal.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { createApp, h } from "vue";

export default (status: number | undefined, message: string) => {
    return errorModal(status, message);
};

const errorModal = (status: number | undefined, message: string) => {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(ErrorModal, {
                        message: message,
                        status: status,
                        onHide: () => resolve(okClicked),
                        onHidden: () => unmount(),
                    });
            },
        }).component("fa-icon", FontAwesomeIcon);

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

    return promise;
};
