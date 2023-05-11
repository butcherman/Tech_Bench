<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <button
                    class="btn btn-sm"
                    title="Refresh Notes"
                    v-tooltip
                    @click="refreshNotes"
                >
                    <fa-icon icon="fa-rotate" :spin="loading" />
                </button>
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
import NewNote from "@/Components/Customer/Notes/NewNote.vue";
import ListNotes from "@/Components/Customer/Notes/ListNotes.vue";
import { ref, reactive, onMounted, inject, provide } from "vue";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleNotesLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type { customerPermissionType, customerNoteType } from "@/Types";

const props = defineProps<{
    notes: customerNoteType[];
}>();

// onMounted(() => console.log(props.notes));

const permission = inject(custPermissionsKey) as customerPermissionType;
const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleNotesLoadKey, toggleLoad);

const refreshNotes = () => {
    console.log("refresh");

    toggleLoad();
    router.reload({
        only: ["flash", "contacts"],
        preserveScroll: true,
        onFinish: () => toggleLoad(),
    });
};
</script>
