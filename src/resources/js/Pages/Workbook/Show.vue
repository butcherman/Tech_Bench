<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";
import { computed, reactive, ref } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    values?: { [index: string]: string };
    wbData: string;
    wbHash: string;
}>();

// TODO - Register to Broadcast Channel and monitor changes in value

const workbook = computed(() => JSON.parse(props.wbData));
const wbValues = ref({ ...props.values });
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
            :values="wbValues"
            :workbook-hash="wbHash"
        />
    </div>
</template>
