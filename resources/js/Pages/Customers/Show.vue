<template>
    <Head :title="customer.name" />
    <div>
        <div class="row">
            <div class="col-md-8">
                <CustomerDetails />
            </div>
            <div class="col-md-4 col-12 mt-md-0 mt-4">
                <div class="float-md-end text-center w-50">
                    <EditCustomer v-if="permissions.details.update" />
                    <!-- <ManageCustomer v-if="permissions.details.manage" /> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <!-- <Equipment /> -->
            </div>
            <div class="col-md-7">
                <!-- <Contacts /> -->
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import EditCustomer from "@/Components/Customer/EditCustomer.vue";
import ManageCustomer from "@/Components/Customer/ManageCustomer.vue";
import Equipment from "@/Components/Customer/Equipment/Equipment.vue";
import Contacts from "@/Components/Customer/Contacts/Contacts.vue";
import { ref, computed, provide, watch, unref } from "vue";
import {
    customerPermissionType,
    customerType,
    customerEquipmentType,
} from "@/Types";

const props = defineProps<{
    permissions: customerPermissionType;
    isFav: boolean;
    customer: customerType;
    equipment: customerEquipmentType[];
}>();

/**
 * Basic shared data between all components
 */
provide('custPermission', props.permissions);
provide('isFav', props.isFav);
provide('allowShare', computed(() => {
    return props.customer.child_count > 0 || props.customer.parent_id !== null;
}));

/**
 * Customer Detail Data
 */
    const custData = ref(props.customer);
    watch(() => props.customer, (newCust) => custData.value = newCust);
provide('customer', custData);

    const allowShare = computed(() => {
        return custData.value.child_count > 0 || custData.value.parent_id !== null;
    });
    provide('allowShare', allowShare);

    /**
     * Customer Equipment Data
     */
    const custEquip = ref(props.equipment);
    watch(() => props.equipment, (newEquip) => custEquip.value = newEquip);
    provide('equipment', custEquip);
</script>

<script lang="ts">
export default { layout: App };
</script>
