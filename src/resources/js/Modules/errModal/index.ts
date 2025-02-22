import errorModal from "./errorModal.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { createApp, h } from "vue";

export default (status: number, message: string) => {
    return errModal(status, message);
};

const errModal = (status: number, message: string) => {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(errorModal, {
                        status,
                        message,
                        onOkClicked: () => (okClicked = true),
                        onHide: () => resolve(okClicked),
                        onHidden: () => unmount(),
                    });
            },
        }).component("fa-icon", FontAwesomeIcon);

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
