import customerAlertModal from "@/Modules/customerAlertModal";
import { customer } from "./CustomerData.module";
import { router } from "@inertiajs/vue3";

interface slugData {
    customer: customer;
    oldSlug: string;
}

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
