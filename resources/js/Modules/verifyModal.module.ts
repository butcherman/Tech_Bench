import { createApp, h } from 'vue';
import VerifyModal      from '@/Components/Base/Modal/VerifyModal.vue';

export function verifyModal(message:string, { title }:{ title?: string } = {})
{
    const promise = new Promise(function(resolve) {

        let yesClicked = false;
        const newComp = createApp({
            setup() {
                return () => h(VerifyModal, {
                    title       : title,
                    message     : message,
                    onYesClicked: () => yesClicked = true,
                    onHide      : () => resolve(yesClicked),
                    onHidden    : () => unmount(),
                });
            }
        });

        /**
         * Mount and show the new OK Modal
         */
        const wrapper = document.createElement('div');
        newComp.mount(wrapper);
        document.body.appendChild(wrapper);

        /**
         * Remove and destroy the modal component
         */
        const unmount = () => {
            newComp.unmount();
            wrapper.remove();
        }
    });

    return promise;
}
