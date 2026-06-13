<script setup lang="ts">
import VueFileForm from "../_Base/VueFileForm.vue";
import { wbHash } from "@/Composables/Workbook/CustomerWorkbook.module.js";

defineEmits<{
    success: [any];
}>();

const props = defineProps<{
    tableIndex: string;
}>();
</script>

<template>
    <VueFileForm
        :initial-values="{}"
        :validation-schema="{}"
        :submit-route="$route('cust-workbook.import', [wbHash, tableIndex])"
        :accepted-files="['text/csv']"
        submit-text="Upload CSV"
        upload-message="Drag CSV File Here"
        file-required
        @success="$emit('success', $event)"
    />
</template>
