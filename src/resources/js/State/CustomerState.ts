import { usePage } from "@inertiajs/vue3";
import { computed, reactive } from "vue";
import { sortCustSites, findPrimarySite } from "@/Modules/CustomerSite.module";

const page = usePage<customerPageProps>();

/*******************************************************************************
 * User Permissions
 *******************************************************************************/
const permissions = computed<customerPermissions>(() => page.props.permissions);

/*******************************************************************************
 * Loading States
 *******************************************************************************/
const loading = reactive({
    site: false,
    equipment: false,
    contacts: false,
    notes: false,
    files: false,
});

const toggleLoading = (
    key: "site" | "equipment" | "contacts" | "notes" | "files"
) => {
    loading[key] = !loading[key];
};

/*******************************************************************************
 * Alert Icons
 *******************************************************************************/
const changeAlert = reactive({
    site: false,
    equipment: false,
    contacts: false,
    notes: false,
    files: false,
});


const clearAlert = (key: "site" | "equipment" | "contacts" | "notes" | "files") => {
    console.log('clear alert called');
    changeAlert[key] = false;
    loading[key] = false;
};

const triggerAlert = (key: "site" | "equipment" | "contacts" | "notes" | "files") => {
    console.log('trigger alert called');
    changeAlert[key] = true;
}

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
const equipmentList = computed<customerEquipment[]>(
    () => page.props.equipmentList
);

/*******************************************************************************
 * Customer Contacts
 *******************************************************************************/
const contacts = computed<customerContact[]>(() => page.props.contacts);

/*******************************************************************************
 * Customer Notes
 *******************************************************************************/
const notes = computed<customerNote[]>(() => page.props.notes);

/*******************************************************************************
 * Customer Files
 *******************************************************************************/
const files = computed<customerFile[]>(() => page.props.files);

/*******************************************************************************
 * Exported Data to Vue Components
 *******************************************************************************/
export {
    permissions,
    loading,
    changeAlert,
    toggleLoading,
    clearAlert,
    triggerAlert,
    customer,
    customerAlerts,
    currentSite,
    primarySite,
    siteList,
    equipmentList,
    contacts,
    notes,
    files,
};
