import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage<customerPageProps>();

/*******************************************************************************
 * All Computed Properties for the customer to be shared across components
 *******************************************************************************/
const customer = computed<customer>(() => page.props.customer);
const siteList = computed<customerSite[]>(() => page.props.siteList);
const permissions = computed<customerPermissions>(() => page.props.permissions);

export { customer, siteList, permissions };
