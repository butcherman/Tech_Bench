<template>
    <VueForm
        ref="newFileForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        submit-text="Upload File"
        @submit="onSubmit"
    >
        <TextInput
            id="name"
            name="name"
            label="Name"
            placeholder="Enter a descriptive name"
        />
        <SelectInput
            id="type"
            name="type"
            label="File Type"
            :optionList="fileTypes"
        />
        <div class="row justify-content-center">
            <div class="col">
                <CheckboxSwitch
                    v-if="allowShare"
                    id="shared"
                    name="shared"
                    label="Shared Across All Linked Sites"
                />
            </div>
        </div>
        <div class="file-container my-2 mb-4">
            <DropzoneInput
                ref="dropzoneInput"
                paramName="file"
                :upload-url="$route('customers.files.store')"
                :max-files="1"
                required
                @file-added="checkNameField"
                @queue-complete="finalizeUpload"
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import DropzoneInput from "@/Components/Base/Input/DropzoneInput.vue";
import { ref, inject } from "vue";
import { object, string, number, boolean } from "yup";
import { customerKey, allowShareKey } from "@/SymbolKeys/CustomerKeys";
import { fileTypesKey } from "@/SymbolKeys/CustomerKeys";
import type { Ref, ComputedRef } from "vue";
import type { DropzoneFile } from "dropzone";

const $route = route;
const newFileForm = ref<InstanceType<typeof VueForm> | null>(null);
const dropzoneInput = ref<InstanceType<typeof DropzoneInput> | null>(null);

const customer = inject<Ref<customer>>(customerKey);
const allowShare = inject<ComputedRef<boolean>>(allowShareKey);
const fileTypes = inject(fileTypesKey, []) as string[];
// const toggleLoad = inject(toggleNotesLoadKey) as () => void;

const validationSchema = object({
    name: string().required(),
    type: string().required(),
    cust_id: number().required(),
    shared: boolean().nullable(),
});
const initialValues = {
    shared: false,
    cust_id: customer?.value.cust_id,
};

/**
 * If the Name field is empty, we will populate it with the filename
 */
const checkNameField = (file: DropzoneFile): void => {
    console.log(file);
    if (
        newFileForm.value?.getFieldValue("name") === undefined ||
        !newFileForm.value?.getFieldValue("name").length
    ) {
        let fileName = file.name;
        newFileForm.value?.setFieldValue("name", fileName);
    }
};

/**
 * Submit the form
 */
const onSubmit = (form: object): void => {
    dropzoneInput.value?.process(form);
};

const finalizeUpload = (): void => {
    newFileForm.value?.endSubmit();
};
</script>
