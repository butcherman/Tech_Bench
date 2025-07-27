<script setup lang="ts">
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import { computed } from "vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import WorkbookBase from "@/Components/Workbook/WorkbookBase.vue";

const props = defineProps<{
    workbookData: string;
    values: { [index: string]: string };
}>();

const wbData = computed(() => JSON.parse(props.workbookData));
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <div class="mb-2">
            <BaseButton
                icon="arrow-left"
                text="Back to Tech Bench"
                :href="$route('workbooks.index')"
            />
        </div>
        <WorkbookBase
            class="grow"
            :workbook-data="wbData"
            :active-page="wbData.body[0].page"
            :values="values"
        />
    </div>
</template>
