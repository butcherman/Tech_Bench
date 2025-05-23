<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import { computed } from "vue";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

const props = defineProps<{
    note: customerNote;
}>();

const editRoute = computed(() => {
    if (props.note.cust_equip_id) {
        return route("customers.equipment.notes.edit", [
            customer.value.slug,
            props.note.cust_equip_id,
            props.note.note_id,
        ]);
    }

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
                <a
                    :href="
                        $route('customers.notes.download', [
                            customer.slug,
                            note.note_id,
                        ])
                    "
                    class="float-end"
                    target="_blank"
                    v-tooltip.left="'Download Note'"
                >
                    <BaseBadge icon="download" />
                </a>
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
