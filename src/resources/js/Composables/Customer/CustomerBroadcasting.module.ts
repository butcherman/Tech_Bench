import customerAlertModal from "@/Modules/customerAlertModal";

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
        .listenToAll((event) => console.log(event));
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
