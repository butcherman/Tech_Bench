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
                <Contacts :contacts="contacts" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <Notes :notes="notes" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <!-- <Files :files="files" /> -->
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
import Notes from "@/Components/Customer/Notes/Notes.vue";
import Files from "@/Components/Customer/Files/Files.vue";
import {
    custPermissionsKey,
    isCustFavKey,
    customerKey,
    allowShareKey,
    fileTypesKey,
    equipTypesKey,
    phoneTypesKey,
} from "@/SymbolKeys/CustomerKeys";
import { ref, computed, provide, watch } from "vue";

const props = defineProps<{
    permissions: customerPermissions;
    isFav: boolean;
    customer: customer;
    equipment: customerEquipment[];
    contacts: customerContact[];
    notes: customerNote[];
    files: []; //  FIXME - type this
    fileTypes: string[];
    equipTypes: categoryList[];
    phoneTypes: phoneNumber[];
}>();

/**
 * Basic shared data between all components
 */
provide(custPermissionsKey, props.permissions);
provide(isCustFavKey, props.isFav);
provide(equipTypesKey, props.equipTypes);
provide(fileTypesKey, props.fileTypes);
provide(phoneTypesKey, props.phoneTypes);

/**
 * Customer Detail Data
 */
const custData = ref<customer>(props.customer);
watch(
    () => props.customer,
    (newCust) => (custData.value = newCust)
);
provide(customerKey, custData);

/**
 * Determine if entries for this customer are allowed to be shared with other customers
 */
const allowShare = computed<boolean>(() => {
    return custData.value.child_count > 0 || custData.value.parent_id !== null;
});
provide(allowShareKey, allowShare);
</script>

<script lang="ts">
export default { layout: App };
</script>
