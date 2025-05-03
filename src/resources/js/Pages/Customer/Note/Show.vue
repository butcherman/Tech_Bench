<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import NoteDetails from "@/Components/Customer/Show/Notes/NoteDetails.vue";
import SiteList from "@/Components/Customer/Show/SiteList.vue";

defineProps<{
    note: customerNote;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex flex-col pb-2 mb-2 border-b border-slate-400">
            <CustomerDetails />
            <div class="text-muted leading-none text-sm pb-1">
                <span class="font-semibold">Note ID: </span>
                {{ note.note_id }}
            </div>
            <div class="text-muted leading-none text-sm">
                <span class="font-semibold">Note Type:</span>
                {{ note.note_type }} Note
            </div>
        </div>
        <div v-if="note.note_type === 'Site'">
            <SiteList title="Note is attached to these sites" />
        </div>
        <NoteDetails :note="note" class="my-2" />
    </div>
</template>
