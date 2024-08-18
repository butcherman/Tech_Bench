<template>
    <VueFileForm
        ref="fileLinkForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('guest-link.update', linkHash)"
        submit-text="Upload Files"
        :max-files="5"
        file-required
        @success="handleSuccess"
    >
        <TextInput id="name" name="name" label="Your Name" focus />
        <template #after-file>
            <TextAreaInput
                id="notes"
                name="notes"
                label="Additional Information"
                :rows="10"
                placeholder="Tell us about this file..."
            />
        </template>
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import { ref } from "vue";
import { object, string } from "yup";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    linkHash: string;
}>();

const fileLinkForm = ref<InstanceType<typeof VueFileForm> | null>(null);

const initValues = {
    name: "",
    notes: "",
};
const schema = object({
    name: string().required(),
    notes: string().nullable(),
});

const handleSuccess = () => {
    let values;
    if ((values = fileLinkForm.value?.values)) {
        const nameField = values.name;
        const formData = useForm(values);
        formData.post(route("guest-link.update", props.linkHash), {
            preserveScroll: true,
            only: ["flash"],
            onFinish: () => {
                fileLinkForm.value?.resetFileForm();
                fileLinkForm.value?.setFieldValue("name", nameField);
                console.log("finished");
            },
        });
    }
};
</script>
