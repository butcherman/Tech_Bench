import Modal from "@/Components/_Base/Modal.vue";
import { customer, toggleAlert } from "@/State/CustomerState";
import { ref } from "vue";

interface alertLink {
    link: string;
    text: string;
}

interface updatedCustomer {
    model: customer;
}

interface updatedSlug {
    customer: customer;
    newSlug: string;
    oldSlug: string;
}

export const openAlert = ref<boolean>(false);
export const customerAlertModal = ref<InstanceType<typeof Modal>|null>(null);
export const customerAlertMessage = ref<string|null>(null);
export const customerAlertLink = ref<alertLink|null>(null);

export const registerCustomerChannel = (slug: string) => {
    Echo.private(`customer.${slug}`)
        .listen('.CustomerUpdated', (data: updatedCustomer) => {
            if(data.model.primary_site_id !== customer.value.primary_site_id) {
                toggleAlert('site');
            }
        })
        .listen('.CustomerSlugChanged', (data: updatedSlug) => {
            customerAlertModal.value?.show();

            customerAlertMessage.value = `Customer Name has been updated.  As a side
                                        effect, the link to view this customer is
                                        no longer valid.  Please use the new link
                                        below to reload the page with the new customer
                                        link.`
            customerAlertLink.value = {
                link: route('customers.show', data.newSlug),
                text: 'Click to Reload Page',
            }
        })
        .listenToAll((event, data) => console.log(event, data));
}
