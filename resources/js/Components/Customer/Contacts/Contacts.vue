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
import { ref, provide, inject } from "vue";
import {
    custPermissionsKey,
    toggleContactsLoadKey,
} from "@/SymbolKeys/CustomerKeys";

defineProps<{
    contacts: customerContact[];
}>();

const permission = inject(custPermissionsKey) as customerPermissions;

/**
 * Loading state of Component
 */
const loading = ref(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleContactsLoadKey, toggleLoad);
</script>
