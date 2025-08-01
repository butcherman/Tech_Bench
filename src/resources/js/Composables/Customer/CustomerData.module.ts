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
export const siteList = computed<customerSite[] | undefined>(() => {
    let siteList = page.props.siteList;
    let primary = customer.value.primary_site_id;

    if (!siteList) {
        return undefined;
    }

    return sortCustSites(siteList, primary);
});
export const currentSite = computed<customerSite>(() => page.props.currentSite);
export const alerts = computed<customerAlert[]>(() => page.props.alerts);

/*
|-------------------------------------------------------------------------------
| Customer Equipment
|-------------------------------------------------------------------------------
*/
export const allowVpn = computed<boolean>(() => page.props.allowVpn);
export const allowShareVpn = computed<boolean>(() => page.props.allowShareVpn);
export const vpnData = computed<vpnData | null>(() => page.props.vpnData);
export const groupedEquipmentList = computed<
    { [key: string]: customerEquipment[] }[]
>(() => page.props.groupedEquipmentList);
export const equipmentList = computed<customerEquipment[]>(
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
export const noteList = computed<customerNote[][]>(() => page.props.noteList);

/*
|-------------------------------------------------------------------------------
| Customer Files
|-------------------------------------------------------------------------------
*/
export const fileTypes = computed<customerFileType[]>(
    () => page.props.fileTypes
);
export const fileList = computed<customerFile[]>(() => page.props.fileList);

/*
|-------------------------------------------------------------------------------
| Internal Methods
|-------------------------------------------------------------------------------
*/

/**
 * Sort the list of sites by putting the primary site on the top.
 */
const sortCustSites = (
    siteList: customerSite[],
    primaryId: number
): customerSite[] => {
    return siteList.sort((x) => (x.cust_site_id === primaryId ? -1 : 1));
};
