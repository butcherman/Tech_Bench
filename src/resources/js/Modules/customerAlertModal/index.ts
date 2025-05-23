import customerAlertModal from "./customerAlertModal.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { createApp, h } from "vue";

export default (updatedRoute: string): Promise<unknown> => {
    return alertModal(updatedRoute);
};

const alertModal = (updatedRoute: string): Promise<unknown> => {
    console.log("opening alert modal");

    const promise = new Promise(function (resolve) {
        const newComp = createApp({
            setup() {
                return () =>
                    h(customerAlertModal, {
                        updatedRoute: updatedRoute,
                        onReload: () => resolve("reloaded"),
                        onHide: () => unmount(),
                    });
            },
        }).component("fa-icon", FontAwesomeIcon);

        const wrapper = document.createElement("div");
        newComp.mount(wrapper);
        document.body.appendChild(wrapper);

        const unmount = () => {
            newComp.unmount();
            wrapper.remove();
        };
    });

    return promise;
};
