<script setup lang="ts">
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import Card from "@/Components/_Base/Card.vue";
import { computed } from "vue";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

const props = defineProps<{
    note: customerNote;
}>();

const editRoute = computed(() => {
    return route("customers.notes.edit", [
        customer.value.slug,
        props.note.note_id,
    ]);
});
</script>

<template>
    <Card>
        <template #title>
            <div class="text-center font-black">
                {{ note.subject }}
            </div>
        </template>
        <div v-html="note.details" />
        <template #footer>
            <div class="flex">
                <div class="grow">
                    <div>Created: {{ note.created_at }}</div>
                    <div>Created By: {{ note.author }}</div>
                    <div v-if="note.updated_author">
                        Last Updated: {{ note.updated_at }}
                    </div>
                    <div v-if="note.updated_author">
                        Updated By: {{ note.updated_author }}
                    </div>
                </div>
                <div>
                    <EditButton
                        v-if="permissions.notes.update"
                        class="m-1 w-24"
                        size="small"
                        :href="editRoute"
                        pill
                    />
                    <DeleteButton
                        v-if="permissions.notes.delete"
                        size="small"
                        class="m-1 w-24"
                        method="delete"
                        :href="
                            $route('customers.notes.destroy', [
                                customer.slug,
                                note.note_id,
                            ])
                        "
                        confirm
                        pill
                    />
                </div>
            </div>
        </template>
    </Card>
</template>
