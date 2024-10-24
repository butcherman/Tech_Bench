<template>
    <li class="list-group-item">
        <strong>Sites</strong> - All sites associated with this
        {{ isEquipment ? "equipment" : "customer" }} are listed here. Click on a
        site to get more specific information about that site
        <span v-if="!isEquipment">
            , or click <AddButton text="Add Site" small pill /> to create a new
            site </span
        >.
    </li>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";

defineProps<{
    isEquipment?: boolean;
}>();
</script>
