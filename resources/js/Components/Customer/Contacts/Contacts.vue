<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['contacts']"
                    @start="toggleLoad"
                    @end="toggleLoad"
                />
                Contacts:
                <NewContact v-if="permission?.contact.create" />
            </div>
            <Overlay :loading="loading">
                <ShowContacts :contacts="contacts" />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewContact from "./NewContact.vue";
import ShowContacts from "./ShowContacts.vue";
import axios from "axios";
import { ref, provide, inject, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleContactsLoadKey,
    phoneTypesKey,
} from "@/SymbolKeys/CustomerKeys";
import type {
    customerContactType,
    customerPermissionType,
    phoneNumberType,
} from "@/Types";

const props = defineProps<{
    contacts: customerContactType[];
}>();

const permission = inject(custPermissionsKey) as customerPermissionType;

/**
 * Loading state of Component
 */
const loading = ref(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleContactsLoadKey, toggleLoad);

/**
 * Types of possible selections for a phone number type
 */
onMounted(() => getPhoneTypes());

const phoneTypes = ref<string[]>([]);
provide(phoneTypesKey, phoneTypes);

//  Ajax call to get the types of phone's (home, mobile, etc.) from Database
const getPhoneTypes = () => {
    if (phoneTypes.value.length === 0) {
        axios.get(route("get-number-types")).then((res) => {
            let names: string[] = [];
            res.data.forEach((item: phoneNumberType) => {
                names.push(item.description);
            });
            phoneTypes.value = names;
        });
    }
};
</script>
