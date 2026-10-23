import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import WhoAreYouModal from "./WhoAreYouModal.vue";
import { createApp, h } from "vue";

export default (): Promise<string> => {
    return whoAreYou();
};

const whoAreYou = (): Promise<string> => {
    const promise: Promise<string> = new Promise(function (resolve) {
        let myName = "";

        const newComp = createApp({
            setup() {
                return () =>
                    h(WhoAreYouModal, {
                        onSubmitted: (res) => (myName = res),
                        onHide: () => resolve(myName),
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
