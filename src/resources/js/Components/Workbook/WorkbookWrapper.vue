<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { onMounted } from "vue";
import { useForm } from "vee-validate";
import { wbHash } from "@/Composables/Workbook/CustomerWorkbook.module.js";

const props = defineProps<{
    workbookSkeleton: workbookWrapper;
    workbookValues: { [key: string]: string };
}>();

const { setFieldValue } = useForm({
    name: "workbook",
    initialValues: props.workbookValues,
});

onMounted(() => {
    Echo.channel(`equipment-workbook.${wbHash.value}`)
        .listen(".WorkbookValueUpdated", (valData: workbookValueEvent) => {
            setFieldValue(valData.model.index, valData.model.value);
            console.log(valData.model.index, valData.model.value);
        })
        .listenToAll((event) => console.log(event));
});
</script>

<template>
    <Card class="h-full">
        <div class="flex flex-col h-full">
            <WorkbookHeader :header-skeleton="workbookSkeleton.header" />
            <form class="grow" novalidate v-focustrap @submit.prevent>
                <WorkbookBody :body-skeleton="workbookSkeleton.body" />
            </form>
            <WorkbookHeader :header-skeleton="workbookSkeleton.footer" />
        </div>
    </Card>
</template>
