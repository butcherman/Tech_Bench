<script setup lang="ts">
import Card from "../_Base/Card.vue";
import Modal from "../_Base/Modal.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { provide, ref, useTemplateRef } from "vue";
import { useForm } from "vee-validate";
import { dataPut } from "@/Composables/axiosWrapper.module";
import { computed } from "@vue/reactivity";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    values?: { [index: string]: string };
    workbookHash?: string;
}>();

const loading = ref(false);
const hasError = ref(false);
const errModal = useTemplateRef("error-modal");

const saveIcon = computed(() => {
    if (hasError.value) {
        return "triangle-exclamation";
    }

    if (loading.value) {
        return "spinner";
    }

    return "circle-check";
});

const saveClass = computed(() => {
    if (hasError.value) {
        return "text-danger";
    }

    if (loading.value) {
        return "text-warning fa-spin";
    }

    return "text-success";
});

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
        hasError.value = false;

        let page = props.workbookData.body.find(
            (p) => p.page === props.activePage
        );

        dataPut(route("customer-workbook.update", props.workbookHash), {
            index,
            fieldValue,
            can_publish: page?.canPublish,
        }).then((res) => {
            loading.value = false;

            if (!res || !res.data.success) {
                errModal.value?.show();
                hasError.value = true;
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
        </form>
        <Modal title="Error" ref="error-modal">
            <h3 class="text-center text-danger">
                Connection to server lost. Changes not saved.
            </h3>
            <p class="text-center">Please refresh page and try again.</p>
        </Modal>
        <template #footer>
            <div class="flex flex-row-reverse">
                <span><fa-icon :icon="saveIcon" :class="saveClass" /></span>
            </div>
        </template>
    </Card>
</template>
