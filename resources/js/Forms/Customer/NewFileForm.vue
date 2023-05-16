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
                name="file"
                class="file-container"
                @filesAdded="checkNameField"
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

const newFileForm = ref(null);
const fileTypes = inject(fileTypesKey, []) as string[];

const validationSchema = object({
    name: string().required(),
    type: string().required(),
    file: array().length(1),
});
const initialValues = {};

const checkNameField = (fileArr) => {
    console.log(fileArr)
    if (
        newFileForm.value.getFieldValue("name") === undefined ||
        !newFileForm.value.getFieldValue("name").length
    ) {
        let fileName = fileArr.name;
        newFileForm.value.setFieldValue("name", fileName);
    }
};





const onSubmit = (form) => {
    console.log(form);

    const formData = useForm(form);
    formData.post(route("customers.files.store"), {
        onFinish: () => console.log("done"),
    });
};
</script>

<style lang="scss">
.file-container {
    height: 200px;
}
</style>
