import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { sortCustSites, findPrimarySite } from "@/Modules/CustomerSite.module";

const page = usePage<customerPageProps>();

/*******************************************************************************
 * User Permissions
 *******************************************************************************/
const permissions = computed<customerPermissions>(() => page.props.permissions);

/*******************************************************************************
 * Customer Information
 *******************************************************************************/
const customer = computed<customer>(() => page.props.customer);
const customerAlerts = computed<customerAlert[]>(() => page.props.alerts);
const primarySite = computed<customerSite | undefined>(() =>
    findPrimarySite(customer.value)
);
const currentSite = computed<customerSite>(() => page.props.site || null);
const siteList = computed<customerSite[]>(() =>
    sortCustSites(page.props.siteList, customer.value.primary_site_id)
);

/*******************************************************************************
 * Exported Data to Vue Components
 *******************************************************************************/
export {
    permissions,
    customer,
    customerAlerts,
    currentSite,
    primarySite,
    siteList,
};
