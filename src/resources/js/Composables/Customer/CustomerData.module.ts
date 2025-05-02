import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage<customerPageProps>();

/*
|-------------------------------------------------------------------------------
| User Data related to customer
|-------------------------------------------------------------------------------
*/
export const isFav = computed<boolean>(() => page.props.isFav);
export const permissions = computed<customerPermissions>(
    () => page.props.permissions
);

/*
|-------------------------------------------------------------------------------
| Customer Data
|-------------------------------------------------------------------------------
*/
export const customer = computed<customer>(() => page.props.customer);
export const siteList = computed<customerSite[]>(() => {
    let siteList = page.props.siteList;
    let primary = customer.value.primary_site_id;

    return sortCustSites(siteList, primary);
});
export const currentSite = null;
export const alerts = computed<customerAlert[]>(() => page.props.alerts);

/*
|-------------------------------------------------------------------------------
| Customer Equipment
|-------------------------------------------------------------------------------
*/
export const equipmentList = computed<{ [key: string]: customerEquipment[] }[]>(
    () => page.props.equipmentList
);

/*
|-------------------------------------------------------------------------------
| Customer Contacts
|-------------------------------------------------------------------------------
*/
export const phoneTypes = computed<phoneType[]>(() => page.props.phoneTypes);
export const contactList = computed<customerContact[]>(
    () => page.props.contactList
);

/*
|-------------------------------------------------------------------------------
| Customer Notes
|-------------------------------------------------------------------------------
*/
export const noteList = computed(() => page.props.noteList);

/*
|-------------------------------------------------------------------------------
| Internal Methods
|-------------------------------------------------------------------------------
*/

/**
 * Return the primary site belonging to this customer.
 */
// const findPrimarySite = (customer: customer): customerSite | undefined => {
//     return customer.customer_site.find(
//         (cust) => cust.cust_id === customer.primary_site_id
//     );
// };

/**
 * Sort the list of sites by putting the primary site on the top.
 */
const sortCustSites = (
    siteList: customerSite[],
    primaryId: number
): customerSite[] => {
    return siteList.sort((x) => (x.cust_site_id === primaryId ? -1 : 1));
};
