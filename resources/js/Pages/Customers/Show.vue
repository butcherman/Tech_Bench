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
                    <ManageCustomer v-if="permissions.details.manage" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <Equipment :equipment="equipment" />
            </div>
            <div class="col-md-7">
                <Contacts />
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
import {
    custPermissionsKey,
    isCustFavKey,
    customerKey,
    allowShareKey,
} from "@/SymbolKeys/CustomerKeys";
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
provide(custPermissionsKey, props.permissions);
provide(isCustFavKey, props.isFav);

/**
 * Customer Detail Data
 */
const custData = ref(props.customer);
watch(
    () => props.customer,
    (newCust) => (custData.value = newCust)
);
provide(customerKey, custData);

/**
 * Determine if entries for this customer are allowed to be shared with other customers
 */
const allowShare = computed(() => {
    return custData.value.child_count > 0 || custData.value.parent_id !== null;
});
provide(allowShareKey, allowShare);
</script>

<script lang="ts">
export default { layout: App };
</script>
