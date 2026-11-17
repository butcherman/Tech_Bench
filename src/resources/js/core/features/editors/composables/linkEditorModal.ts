import LinkDataModal from "../components/LinkDataModal.vue";
import { createApp, h } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { vOnClickOutside } from "@vueuse/components";
import { useLinkEditorHelper } from "./linkEditorHelper.js";
import type { Editor } from "@tiptap/vue-3";

export const linkEditorModal = (editor: Editor) => {
    const linkEditor = useLinkEditorHelper(editor);
    const linkData = linkEditor.getLinkData();

    let unmount: () => void;

    const linkModal = createApp({
        setup() {
            return () =>
                h(LinkDataModal, {
                    text: linkData.text,
                    url: linkData.url,

                    onSetLink: (data) => {
                        linkEditor.setLink(data);
                        unmount();
                    },

                    onRemoveLink: () => {
                        linkEditor.removeLink();
                        unmount();
                    },

                    onHidden: () => unmount(),
                });
        },
    })
        .component("fa-icon", FontAwesomeIcon)
        .directive("on-click-outside", vOnClickOutside);

    const wrapper = document.createElement("div");

    linkModal.mount(wrapper);
    document.body.appendChild(wrapper);

    unmount = () => {
        linkModal.unmount();
        wrapper.remove();
    };

    return true;
};
