import { createApp, h } from "vue";
import OkModal from "@/Components/Base/Modal/OkModal.vue";

export function okModal(message: string, { title }: { title?: string } = {}) {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(OkModal, {
                        title: title !== undefined ? title : message,
                        message: title !== undefined ? message : null,
                        onOkClicked: () => (okClicked = true),
                        onOkHide: () => resolve(okClicked),
                        onOkHidden: () => unmount(),
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
}
