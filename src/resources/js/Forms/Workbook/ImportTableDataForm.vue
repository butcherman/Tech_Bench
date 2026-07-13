<script setup lang="ts">
import VueFileForm from "../_Base/VueFileForm.vue";
import { wbHash } from "@/Composables/Workbook/CustomerWorkbook.module.js";

defineEmits<{
    success: [string];
}>();

const props = defineProps<{
    tableIndex: string;
    isPagePublic: boolean;
}>();
</script>

<template>
    <VueFileForm
        :initial-values="{ publicPage: isPagePublic }"
        :validation-schema="{}"
        :submit-route="
            $route('cust-workbook.import.store', [wbHash, tableIndex])
        "
        :accepted-files="['text/csv']"
        submit-text="Upload CSV"
        upload-message="Drag CSV File Here"
        file-required
        @success="$emit('success', $event)"
    />
</template>
