import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { sortCustSites, findPrimarySite } from "@/Modules/CustomerSite.module";

const page = usePage<customerPageProps>();

/*******************************************************************************
 * User Permissions
 *******************************************************************************/
const permissions = computed<customerPermissions>(() => page.props.permissions);

/*******************************************************************************
 * Loading States
 *******************************************************************************/
const loading = {
    site: ref(false),
    equipment: ref(false),
};

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
 * Customer Equipment
 *******************************************************************************/
const equipment = computed<customerEquipment[]>(() => page.props.equipment);

/*******************************************************************************
 * Exported Data to Vue Components
 *******************************************************************************/
export {
    permissions,
    loading,
    customer,
    customerAlerts,
    currentSite,
    primarySite,
    siteList,
    equipment,
};
