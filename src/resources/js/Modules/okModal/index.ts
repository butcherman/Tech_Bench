import OkModal from "./okModal.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import { createApp, h } from "vue";

export default (message: string, forceOk: boolean = false) => {
    return okModal(message, forceOk);
};

const okModal = (message: string, forceOk: boolean) => {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(OkModal, {
                        message: message,
                        forceOk: forceOk,
                        onOkClicked: () => (okClicked = true),
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
