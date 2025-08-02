<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { computed } from "vue";

const props = defineProps<{
    values?: { [index: string]: string };
    wbData: string;
    wbHash: string;
}>();

const workbook = computed(() => JSON.parse(props.wbData));
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <WorkbookBase
            class="h-full"
            :workbook-data="workbook"
            :active-page="workbook.body[0].page"
            :values="values"
            :workbook-hash="wbHash"
        />
    </div>
</template>
