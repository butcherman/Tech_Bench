import { ref, computed } from 'vue';

interface customerProps {
    permissions: customerPermissions;
    isFav: boolean;
    customer: customer;
    equipment: customerEquipment[];
    contacts: customerContact[];
    notes: customerNote[];
    files: customerFile[];
    fileTypes: string[];
    equipTypes: categoryList[];
    phoneTypes: phoneNumber[];
}

/**
 * State Variables
 */
export const customer = ref<customer>();
export const equipment = ref<customerEquipment[]>();
export const permissions = ref<customerPermissions>();
export const equipTypes = ref();
export const isFav = ref<boolean>(false);
export const allowShare = computed<boolean>(() => {
    return customer.value?.child_count!! > 0 || customer.value?.parent_id !== null;
});

/**
 * Build the viewable state
 */
export const updateState = (newState: customerProps) => {
    customer.value = newState.customer;
    equipment.value = newState.equipment;
    permissions.value = newState.permissions;
    isFav.value = newState.isFav;
    equipTypes.value = newState.equipTypes;
}

/**
 * Various Loading States
 */
export const manageLoad = ref(false);
export const equipLoad = ref(false);


export const toggleManageLoad = () => {
    manageLoad.value = !manageLoad.value;
}

export const toggleEquipLoad = () => {
    equipLoad.value = !equipLoad.value;
}
