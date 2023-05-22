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
import CheckboxSwitch from '@/Components/Base/Input/CheckboxSwitch.vue';
import DropzoneInput from "@/Components/Base/Input/DropzoneInput.vue";
import { ref, reactive, onMounted, inject } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string, number, boolean } from "yup";
import { customerKey, allowShareKey } from '@/SymbolKeys/CustomerKeys';
import { fileTypesKey } from "@/SymbolKeys/CustomerKeys";

const $route = route;
const customer = inject(customerKey) as Ref<customerType>;
const allowShare = inject(allowShareKey) as ComputedRef<boolean>;
// const toggleLoad = inject(toggleNotesLoadKey) as () => void;
const newFileForm = ref<InstanceType<typeof VueForm> | null>(null);
const dropzoneInput = ref<InstanceType<typeof DropzoneInput> | null>(null);
const fileTypes = inject(fileTypesKey, []) as string[];

const validationSchema = object({
    name: string().required(),
    type: string().required(),
    cust_id: number().required(),
    shared: boolean().nullable(),
});
const initialValues = {
    shared: false,
    cust_id: customer.value.cust_id,
};

/**
 * If the Name field is empty, we will populate it with the filename
 */
const checkNameField = (file) => {
    if (
        newFileForm.value.getFieldValue("name") === undefined ||
        !newFileForm.value.getFieldValue("name").length
    ) {
        let fileName = file.name;
        newFileForm.value.setFieldValue("name", fileName);
    }
};

/**
 * Submit the form
 */
const onSubmit = (form) => {
    console.log(form);

    dropzoneInput.value.process(form);

    // const formData = useForm(form);
    // formData.post(route("customers.files.store"), {
    //     onFinish: () => console.log("done"),
    // });
};

const finalizeUpload = () => {
    console.log('completed');
    newFileForm.value.endSubmit();
}
</script>

<style lang="scss">
// .file-container {
//     min-height: 200px;
// }
</style>
