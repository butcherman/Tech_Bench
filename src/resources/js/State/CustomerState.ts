import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
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
const primarySite = computed(() => findPrimarySite(customer.value));
const currentSite = computed(() => page.props.site);
const siteList = computed<customerSite[]>(() =>
    sortCustSites(page.props.siteList, customer.value.primary_site_id)
);

/*******************************************************************************
 * Exported Data to Vue Components
 *******************************************************************************/
export { customer, primarySite, currentSite, siteList, permissions };
