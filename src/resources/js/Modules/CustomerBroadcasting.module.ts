import Modal from "@/Components/_Base/Modal.vue";
import { customer, triggerAlert } from "@/State/CustomerState";
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
export const customerAlertModal = ref<InstanceType<typeof Modal> | null>(null);
export const customerAlertMessage = ref<string | null>(null);
export const customerAlertLink = ref<alertLink | null>(null);

/**
 * Channel for all Customer Related Events
 */
export const registerCustomerChannel = (slug: string) => {
    Echo.private(`customer.${slug}`)
        .listen(".CustomerUpdated", (data: updatedCustomer) => {
            if (data.model.primary_site_id !== customer.value.primary_site_id) {
                triggerAlert("site");
            }
        })
        .listen(".CustomerSlugChanged", (data: updatedSlug) =>
            triggerCustomerSlugChanged(data)
        )
        .listen(".CustomerSiteCreated", () => triggerAlert("site"))
        .listen(".CustomerSiteUpdated", () => triggerAlert("site"))
        .listen(".customerSiteDeleted", () => triggerAlert("site"))
        .listen(".CustomerEquipmentCreated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentUpdated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentDeleted", () => triggerAlert("equipment"))
        .listen(".CustomerContactCreated", () => triggerAlert("contacts"))
        .listen(".CustomerContactUpdated", () => triggerAlert("contacts"))
        .listen(".CustomerContactDeleted", () => triggerAlert("contacts"))
        .listen(".CustomerNoteCreated", () => triggerAlert("notes"))
        .listen(".CustomerNoteUpdated", () => triggerAlert("notes"))
        .listen(".CustomerNoteDeleted", () => triggerAlert("notes"))
        .listen(".CustomerFileCreated", () => triggerAlert("files"))
        .listen(".CustomerFileUpdated", () => triggerAlert("files"))
        .listen(".CustomerFileDeleted", () => triggerAlert("files"));
};

/**
 * Channel for all Site Related Events
 */
export const registerSiteChannel = (siteSlug: string) => {
    Echo.private(`customer-site.${siteSlug}`)
        .listen(".CustomerSlugChanged", (data: updatedSlug) =>
            triggerCustomerSlugChanged(data)
        )
        .listen(".CustomerSiteCreated", () => triggerAlert("site"))
        .listen(".CustomerSiteUpdated", () => triggerAlert("site"))
        .listen(".customerSiteDeleted", () => triggerAlert("site"))
        .listen(".CustomerEquipmentCreated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentUpdated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentDeleted", () => triggerAlert("equipment"))
        .listen(".CustomerContactCreated", () => triggerAlert("contacts"))
        .listen(".CustomerContactUpdated", () => triggerAlert("contacts"))
        .listen(".CustomerContactDeleted", () => triggerAlert("contacts"))
        .listen(".CustomerNoteCreated", () => triggerAlert("notes"))
        .listen(".CustomerNoteUpdated", () => triggerAlert("notes"))
        .listen(".CustomerNoteDeleted", () => triggerAlert("notes"))
        .listen(".CustomerFileCreated", () => triggerAlert("files"))
        .listen(".CustomerFileUpdated", () => triggerAlert("files"))
        .listen(".CustomerFileDeleted", () => triggerAlert("files"));
};

/**
 * Channel for all Customer Equipment Related Events
 */
export const registerEquipmentChannel = (custEquipId: number) => {
    Echo.private(`customer-equipment.${custEquipId}`)
        .listen(".CustomerEquipmentCreated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentUpdated", () => triggerAlert("equipment"))
        .listen(".CustomerEquipmentDeleted", () => triggerAlert("equipment"))
        .listen(".CustomerNoteCreated", () => triggerAlert("notes"))
        .listen(".CustomerNoteUpdated", () => triggerAlert("notes"))
        .listen(".CustomerNoteDeleted", () => triggerAlert("notes"))
        .listen(".CustomerFileCreated", () => triggerAlert("files"))
        .listen(".CustomerFileUpdated", () => triggerAlert("files"))
        .listen(".CustomerFileDeleted", () => triggerAlert("files"))
        .listen(".CustomerEquipmentDataUpdated", () =>
            triggerAlert("equipment")
        );
};

/**
 * Display an alert telling the user that the link to this customer has been
 * changed
 */
const triggerCustomerSlugChanged = (data: updatedSlug) => {
    customerAlertModal.value?.show();

    customerAlertMessage.value = `Customer Name has been updated.  As a side
                                effect, the link to view this customer is
                                no longer valid.  Please use the new link
                                below to reload the page with the new customer
                                link.`;
    customerAlertLink.value = {
        link: route("customers.show", data.newSlug),
        text: "Click to Reload Page",
    };
};
