<template>
    <div>
        <h5 class="text-center">
            <span
                v-if="note.urgent"
                class="float-start"
                title="This note was marked with high importance"
                v-tooltip
            >
                <fa-icon icon="exclamation-circle" class="text-danger" />
            </span>
            {{ note.subject }}
            <a
                :href="
                    $route('customers.notes.download', [
                        customer.cust_id,
                        note.note_id,
                    ])
                "
                target="_blank"
            >
                <span
                    class="badge bg-info float-end pointer mx-1"
                    title="Download Note"
                    v-tooltip
                >
                    <fa-icon icon="fa-download" />
                </span>
            </a>
            <Link v-if="!isExpanded" :href="getShowRoute()">
                <span
                    class="badge bg-info float-end mx-1"
                    title="Open Full Note"
                    v-tooltip
                >
                    <fa-icon icon="square-arrow-up-right" />
                </span>
            </Link>
        </h5>
        <hr />
        <div class="note-details" v-html="note.details" />
        <hr />
        <div class="text-muted">
            <div v-if="note.equipment_type">
                Equipment: {{ note.equipment_type.equip_name }}
            </div>
            <div>
                Created: {{ note.created_at }} by
                {{ note.author }}
            </div>
            <div v-if="note?.updated_author">
                Updated: {{ note.updated_at }} by
                {{ note.updated_author }}
            </div>
        </div>
        <div v-if="!isExpanded" class="text-center tiny-text mt-2">
            To update this note, click
            <fa-icon icon="square-arrow-up-right" class="text-info" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { customer, currentSite } from "@/State/CustomerState";

const props = defineProps<{
    note: customerNote;
    equipment?: customerEquipment;
    isExpanded?: boolean;
}>();

const getShowRoute = () => {
    if (props.equipment) {
        return route("customers.equipment.notes.show", [
            customer.value.slug,
            props.equipment.cust_equip_id,
            props.note.note_id,
        ]);
    }

    if (currentSite.value) {
        return route("customers.site.notes.show", [
            customer.value.slug,
            currentSite.value.site_slug,
            props.note.note_id,
        ]);
    }

    return route("customers.notes.show", [
        customer.value.slug,
        props.note.note_id,
    ]);
};
</script>

<style scoped lang="scss">
.tiny-text {
    font-size: small;
}
</style>
