<script setup lang="ts">
import Card from "../_Base/Card.vue";
import Modal from "../_Base/Modal.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { provide, ref, useTemplateRef } from "vue";
import { useForm } from "vee-validate";
import { dataPut } from "@/Composables/axiosWrapper.module";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    isPreview?: boolean;
    values?: { [index: string]: string };
    workbookHash?: string;
}>();

const loading = ref(false);
const errModal = useTemplateRef("error-modal");

const { values } = useForm({
    initialValues: props.values,
});

/**
 * Save an individual field
 */
const triggerSave = (index: string): void => {
    if (props.workbookHash) {
        let fieldValue = values[index];
        loading.value = true;

        dataPut(route("customer-workbook.update", props.workbookHash), {
            index,
            fieldValue,
        }).then((res) => {
            loading.value = false;

            if (!res || !res.data.success) {
                errModal.value?.show();
            }
        });
    }
};

provide("triggerSave", triggerSave);
</script>

<template>
    <Card>
        <form novalidate @submit.prevent class="h-full flex flex-col">
            <WorkbookHeader :data="workbookData.header" />
            <WorkbookBody
                class="grow"
                :workbook-data="workbookData"
                :active-page="activePage"
                :values="values"
            />
            <WorkbookHeader :data="workbookData.footer" />
            <Modal title="Error" ref="error-modal">
                <h3 class="text-center text-danger">
                    Connection to server lost. Changes not saved.
                </h3>
                <p class="text-center">Please refresh page and try again.</p>
            </Modal>
        </form>
    </Card>
</template>
