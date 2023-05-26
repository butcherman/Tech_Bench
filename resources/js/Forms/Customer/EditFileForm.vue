<template>
    <VueForm
        ref="editFileForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        submit-text="Update File"
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
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import { ref, inject } from "vue";
import { useForm } from "@inertiajs/vue3";
import { customerKey, fileTypesKey, allowShareKey, toggleFilesLoadKey } from "@/SymbolKeys/CustomerKeys";
import { object, string, boolean } from "yup";
import type { Ref, ComputedRef } from "vue";

const emit = defineEmits(["success"]);
const props = defineProps<{
    file: customerFile;
}>();

const editFileForm = ref<InstanceType<typeof VueForm> | null>(null);
const allowShare = inject<ComputedRef<boolean>>(allowShareKey);
const fileTypes = inject<string[]>(fileTypesKey, []);
const customer = inject<Ref<customer>>(customerKey);
const toggleLoad = inject(toggleFilesLoadKey) as () => void;

const validationSchema = object({
    name: string().required(),
    type: string().required(),
    shared: boolean().nullable(),
});
const initialValues = {
    shared: props.file.shared,
    name: props.file.name,
    type: props.file.file_type,
    cust_id: customer?.value.cust_id,
};

const onSubmit = (form: customerFile) => {
    toggleLoad();
    const formData = useForm(form);
    formData.put(route("customers.files.update", props.file.cust_file_id), {
        preserveScroll: true,
        only: ["files", "flash"],
        onSuccess: (page) => console.log(page),
        onFinish: () => {
            editFileForm.value?.endSubmit();
            toggleLoad();
            emit("success");
        },
        onError: (err) => console.log(err),
    });
};
</script>
