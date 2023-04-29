import { createApp, h }    from 'vue';
import { vFocusDirective } from '@/Directives/Focus';
import CustomerSearchModal from '@/Components/Customer/CustomerSearchModal.vue';

export function customerSearchBox(nameParam:string = '')
{
    const promise = new Promise(function(resolve) {

        const newComp = createApp({
            setup() {
                return () => h(CustomerSearchModal, {
                    initialSearch: nameParam,
                    onHidden     : () => unmount(),
                    onSelected   : (cust) => resolve(cust),
                });
            }
        });

        newComp.directive('focus', vFocusDirective);

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
