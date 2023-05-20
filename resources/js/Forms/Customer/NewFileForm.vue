<template>
    <VueForm
        ref="newFileForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        submit-text="Upload File"
        @submit="onSubmit"
    >
        <TextInput id="name" name="name" label="Name" placeholder="Enter a descriptive name" />
        <SelectInput
            id="type"
            name="type"
            label="File Type"
            :optionList="fileTypes"
        />
        <div class="file-container my-2 mb-4">
            <DropzoneInput
                ref="dropzoneInput"
                name="file"
                :upload-url="$route('customers.files.store')"
                :max-files="1"
                @file-added="checkNameField"
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import DropzoneInput from "@/Components/Base/Input/DropzoneInput.vue";
import { ref, reactive, onMounted, inject } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string, array } from "yup";
import { fileTypesKey } from "@/SymbolKeys/CustomerKeys";

const $route = route;
const newFileForm = ref(null);
const dropzoneInput = ref(null);
const fileTypes = inject(fileTypesKey, []) as string[];

const validationSchema = object({
    name: string().required(),
    type: string().required(),
    file: array().length(1),
});
const initialValues = {};

const checkNameField = (file) => {
    if (
        newFileForm.value.getFieldValue("name") === undefined ||
        !newFileForm.value.getFieldValue("name").length
    ) {
        let fileName = file.name;
        newFileForm.value.setFieldValue("name", fileName);
    }
};





const onSubmit = (form) => {
    console.log(form);

    dropzoneInput.value.process();

    // const formData = useForm(form);
    // formData.post(route("customers.files.store"), {
    //     onFinish: () => console.log("done"),
    // });
};
</script>

<style lang="scss">
// .file-container {
//     min-height: 200px;
// }
</style>
