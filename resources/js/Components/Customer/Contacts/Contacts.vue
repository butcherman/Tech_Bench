<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['contacts']"
                    @start="toggleContLoad"
                    @end="toggleContLoad"
                />
                Contacts:
                <NewContact v-if="permissions?.contact.create" />
            </div>
            <Overlay :loading="contactLoad">
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
import {
    permissions,
    contactLoad,
    toggleContLoad,
} from "@/State/Customer/CustomerState";

defineProps<{
    contacts: customerContact[];
}>();
</script>
