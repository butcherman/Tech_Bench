<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import WorkbookWrapper from "@/Components/Workbook/WorkbookWrapper.vue";
import { Deferred } from "@inertiajs/vue3";
import { initWorkbook } from "@/Composables/Workbook/CustomerWorkbook.module";
import { onMounted } from "vue";

const props = defineProps<{
    customer: customer;
    workbook: customerWorkbook;
    workbookValues?: { [key: string]: string };
    taskLists?: workbookTaskList[];
}>();

onMounted(() => {
    initWorkbook(props.workbook, true);
});
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="grow">
        <Deferred data="workbook-values">
            <template #fallback>
                <Card class="h-full">
                    <div class="flex flex-col justify-center h-full">
                        <AtomLoader text="Loading Workbook" />
                    </div>
                </Card>
            </template>
            <WorkbookWrapper
                v-if="workbookValues && workbook.public_workbook && taskLists"
                :workbook-skeleton="workbook.public_workbook"
                :workbook-values="workbookValues"
                :task-lists="taskLists"
            />
        </Deferred>
    </div>
</template>
