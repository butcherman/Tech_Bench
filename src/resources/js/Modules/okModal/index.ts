import OkModal from "./OkModal.vue";
import { createApp, h } from "vue";

export default (message: string) => {
    return okModal(message);
};

const okModal = (message: string) => {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(OkModal, {
                        message: message,
                        onOkClicked: () => (okClicked = true),
                        onHide: () => resolve(okClicked),
                        onHidden: () => unmount(),
                    });
            },
        });

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
