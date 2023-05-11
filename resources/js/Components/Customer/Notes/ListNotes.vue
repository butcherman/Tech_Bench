<template>
    <div v-if="!notes.length">
        <h5 class="text-center">No Notes Assigned to this Customer</h5>
    </div>
    <div v-else>
        <Link
            as="div"
            :href="$route('customers.notes.show', note.note_id)"
            v-for="note in notes"
            :key="note.note_id"
            class="card customer-note-minimized my-2 pointer"
        >
            <div class="card-body">
                <div class="card-title">
                    {{ note.subject }}
                </div>
                <div class="note-details" v-html="note.details" />
            </div>
        </Link>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import type { customerNoteType } from "@/Types";

const props = defineProps<{
    notes: customerNoteType[];
}>();

const $route = route;
</script>

<style lang="scss">
.customer-note-minimized {
    overflow: hidden;
    .note-details {
        max-height: 50px;
    }
}
</style>
