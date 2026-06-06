<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
import ShowHeader from "@/Components/Workbook/ShowHeader.vue";
import WorkbookWrapper from "@/Components/Workbook/WorkbookWrapper.vue";
import { initWorkbook } from "@/Composables/Workbook/CustomerWorkbook.module";
import { onMounted } from "vue";
import { Deferred } from "@inertiajs/vue3";

const props = defineProps<{
    customer: customer;
    equipment: customerEquipment;
    workbook: customerWorkbook;
    workbookValues?: { [key: string]: string };
}>();

onMounted(() => {
    initWorkbook(props.workbook);

    console.log(props.workbook);
});
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex flex-col h-full">
        <ShowHeader
            :customer="customer"
            :equipment="equipment"
            :workbook="workbook"
        />
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
                    v-if="workbookValues"
                    :workbook-skeleton="workbook.wb_skeleton"
                    :workbook-values="workbookValues"
                />
            </Deferred>
        </div>
    </div>
</template>
