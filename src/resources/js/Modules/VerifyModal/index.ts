import VerifyModal from "./VerifyModal.vue";
import { createApp, h } from "vue";
import { createVuetify } from "vuetify/lib/framework.mjs";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export default (message?: string, title?: string) => {
    return verifyModal({ title, message });
};

interface verifyObject {
    title?: string;
    message?: string;
}

const vuetify = createVuetify();
const verifyModal = ({ title, message }: verifyObject) => {
    const promise = new Promise(function (resolve) {
        let yesClicked = false;
        const newComp = createApp({
            setup() {
                return () =>
                    h(VerifyModal, {
                        title: title ? title : "Are you sure?",
                        message: message ? message : "Please Verify",
                        onYesClicked: () => (yesClicked = true),
                        onHide: () => resolve(yesClicked),
                        onHidden: () => unmount(),
                    });
            },
        })
            .use(vuetify)
            .component("font-awesome-icon", FontAwesomeIcon);

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
