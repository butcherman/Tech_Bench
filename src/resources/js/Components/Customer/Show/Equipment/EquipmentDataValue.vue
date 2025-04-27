import EquipmentData from
'@/Components/Customer/Show/Equipment/EquipmentData.vue';
<script setup lang="ts">
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import { permissions } from "@/Composables/Customer/CustomerData.module";

const emit = defineEmits<{
    "edit-field": [customerEquipmentData];
}>();

const props = defineProps<{
    data: customerEquipmentData;
}>();
</script>

<template>
    <div class="flex flex-row">
        <div class="grow">{{ data.value }}</div>
        <div>
            <EditBadge
                v-if="permissions.equipment.update"
                @click="$emit('edit-field', data)"
            />
        </div>
    </div>
</template>
