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
export const currentSite = null;
