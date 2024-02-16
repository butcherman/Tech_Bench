import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage<customerPageProps>();

/*************************************************************************
 * All Computed Properties for the customer to be shared across components
 *************************************************************************/
export const customer = computed<customer>(() => page.props.customer);
export const siteList = computed<customerSite[]>(() => page.props.siteList);
export const permissions = computed<customerPermissions>(
    () => page.props.permissions
);
