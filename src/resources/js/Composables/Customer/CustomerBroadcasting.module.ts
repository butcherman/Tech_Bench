import customerAlertModal from "@/Modules/customerAlertModal";
import { customer } from "./CustomerData.module";
import { router } from "@inertiajs/vue3";
import { reactive } from "vue";

interface slugData {
    customer: customer;
    oldSlug: string;
}

type alertKey = "site" | "equipment" | "contacts" | "notes";

/*
|-------------------------------------------------------------------------------
| Alert Button State
|-------------------------------------------------------------------------------
*/
export const notificationStatus = reactive({
    site: false,
    equipment: false,
    contacts: false,
    notes: false,
});

const triggerNotification = (key: alertKey): void => {
    notificationStatus[key] = true;
};

export const clearNotification = (key: alertKey): void => {
    notificationStatus[key] = false;
};

/*
|-------------------------------------------------------------------------------
| Register to A Customer Channel
|-------------------------------------------------------------------------------
*/
export const registerCustomerChannel = (slug: string): void => {
    console.log(`Registering to customer.${slug}`);

    Echo.private(`customer.${slug}`)
        .listen(".CustomerSlugChanged", (data: slugData) => onSlugChanged(data))
        .listen(".CustomerUpdated", (data: { model: customer }) =>
            onCustomerUpdated(data)
        )
        .listen(".CustomerSiteCreated", () => triggerNotification("site"))
        .listen(".CustomerSiteUpdated", () => triggerNotification("site"))
        .listen(".customerSiteDeleted", () => triggerNotification("site"))
        .listen(".CustomerEquipmentCreated", () =>
            triggerNotification("equipment")
        )
        .listen(".CustomerEquipmentUpdated", () =>
            triggerNotification("equipment")
        )
        .listen(".CustomerEquipmentDeleted", () =>
            triggerNotification("equipment")
        )
        .listen(".CustomerContactCreated", () =>
            triggerNotification("contacts")
        )
        .listen(".CustomerContactUpdated", () =>
            triggerNotification("contacts")
        )
        .listen(".CustomerContactDeleted", () =>
            triggerNotification("contacts")
        )
        .listenToAll((event, data) => console.log(event, data));
};

export const leaveCustomerChannel = (slug: string): void => {
    Echo.leave(`customer.${slug}`);
};

/**
 * When a Customers Name is changed, its URL will also change.  Notify all
 * users of this change and link them to the proper page.
 */
const onSlugChanged = (data: slugData) => {
    customerAlertModal(route("customers.show", data.customer.slug));
};

/**
 * When the Customers Main Information has changed, reload that portion of the
 * page to refresh the data.
 */
const onCustomerUpdated = (data: { model: customer }) => {
    if (data.model.primary_site_id !== customer.value.primary_site_id) {
        console.log("primary site changed");
        router.reload({ only: ["siteList"] });
    } else {
        router.reload({ only: ["customer"] });
    }
};
