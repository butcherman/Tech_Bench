import { createApp, h }     from 'vue';
import LinkedCustomersModal from '@/Components/Customer/LinkedCustomersModal.vue';

export function linkedCustomers(slug:string, name:string)
{
    const promise = new Promise(function(resolve) {
        const newComp = createApp({
            setup() {
                return () => h(LinkedCustomersModal, {
                    slug  : slug,
                    name  : name,
                    onHide: () => unmount(),
                });
            }
        });

        /**
         * Mount and show the new OK Modal
         */
        const wrapper = document.createElement('div');
        newComp.config.globalProperties.route = window.route;
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
