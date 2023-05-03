import { createApp, h } from "vue";
import { vFocusDirective } from "@/Directives/Focus";
import CustomerSearchModal from "@/Components/Customer/CustomerSearchModal.vue";
import { customerType } from "@/Types";
import { FontAwesomeIcon }              from '@fortawesome/vue-fontawesome';
import { vTooltipDirective }            from '@/Directives/Tooltip';


export function customerSearchBox(
    nameParam: string = ""
): Promise<customerType> {
    const promise: Promise<customerType> = new Promise(function (resolve) {
        const newComp = createApp({
            setup() {
                return () =>
                    h(CustomerSearchModal, {
                        initialSearch: nameParam,
                        onHidden: () => unmount(),
                        onSelected: (cust) => resolve(cust),
                    });
            },
        }).component('fa-icon', FontAwesomeIcon)
        .directive('tooltip', vTooltipDirective);

        newComp.directive("focus", vFocusDirective);

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
