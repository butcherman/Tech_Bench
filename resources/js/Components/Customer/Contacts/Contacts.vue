<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <button
                    class="btn btn-sm"
                    title="Refresh Equipment"
                    v-tooltip
                    @click="refreshContacts"
                >
                    <fa-icon icon="fa-rotate" :spin="loading" />
                </button>
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
import type { customerContactType, customerPermissionType, phoneNumberType } from "@/Types";

const props = defineProps<{
    contacts: customerContactType[];
}>();

const permission = inject(custPermissionsKey) as customerPermissionType;
const loading = ref(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleContactsLoadKey, toggleLoad);

/**
 * Check for new model values
 */
const refreshContacts = () => {
    toggleLoad();
    router.get(route("customers.contacts.index"), {
        only: ["flash", "contacts"],
    });
};

/**
 * Types of possible selections for a phone number type
 */
onMounted(() => getPhoneTypes());
const phoneTypes = ref<string[]>([]);
provide(phoneTypesKey, phoneTypes);
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
