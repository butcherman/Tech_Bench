import { createApp, h } from "vue";
import HelpModal from "@/Components/Base/Modal/HelpModal.vue";

export function helpModal(message: string, { title }: { title?: string } = {}) {
    const promise = new Promise(function (resolve) {
        let okClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(HelpModal, {
                        title: title !== undefined ? title : "Help",
                        message: message,
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
