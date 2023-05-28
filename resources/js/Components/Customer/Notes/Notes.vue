<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['notes']"
                    @start="toggleLoad"
                    @end="toggleLoad"
                />
                Notes:
                <NewNote v-if="permission?.notes.create" />
            </div>
            <Overlay :loading="loading">
                <ListNotes :notes="notes" />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewNote from "@/Components/Customer/Notes/NewNote.vue";
import ListNotes from "@/Components/Customer/Notes/ListNotes.vue";
import { ref, inject, provide } from "vue";
import {
    custPermissionsKey,
    toggleNotesLoadKey,
} from "@/SymbolKeys/CustomerKeys";

defineProps<{
    notes: customerNote[];
}>();

const permission = inject(custPermissionsKey) as customerPermissions;

/**
 * Loading State of Component
 */
const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleNotesLoadKey, toggleLoad);
</script>
