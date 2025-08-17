<script setup lang="ts">
defineEmits<{
    edit: [];
    clone: [];
    delete: [];
}>();

defineProps<{
    canEdit: boolean;
}>();
</script>

<template>
    <div class="absolute end-0 -top-1 text-xs gap-1">
        <span
            v-if="canEdit"
            class="text-warning pointer"
            v-tooltip.bottom="'Edit Component Data'"
            @click="$emit('edit')"
        >
            <fa-icon icon="pencil" />
        </span>
        <span
            class="text-primary pointer"
            v-tooltip.bottom="'Clone Component'"
            @click="$emit('clone')"
        >
            <fa-icon icon="clone" />
        </span>
        <span
            class="text-danger pointer"
            v-tooltip.bottom="'Delete Component'"
            @click="$emit('delete')"
        >
            <fa-icon icon="trash-alt" />
        </span>
    </div>
</template>
