<template>
    <VueForm
        ref="noteForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        :submit-text="submitText"
        @submit="onSubmit"
    >
        <TextInput id="subject" name="subject" label="Title" focus />
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <CheckboxSwitch
                    id="urgent"
                    name="urgent"
                    label="Mark Notes As Important"
                />
                <CheckboxSwitch
                    v-if="allowShare"
                    id="shared"
                    name="shared"
                    label="Shared Across All Linked Sites"
                />
            </div>
        </div>
        <Editor id="details" name="details" label="Note Details" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import CheckboxSwitch from "@/Components/Base/Input/CheckboxSwitch.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import Editor from "@/Components/Base/Input/Editor.vue";
import { ref, inject, computed } from "vue";
import {
    customerKey,
    allowShareKey,
    toggleNotesLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import { object, string, boolean } from "yup";
import { useForm } from "@inertiajs/vue3";
import type { Ref, ComputedRef } from 'vue';

const emit = defineEmits(["success"]);
const props = defineProps<{
    noteData?: customerNote;
}>();

const customer = inject(customerKey) as Ref<customer>;
const allowShare = inject(allowShareKey) as ComputedRef<boolean>;
const toggleLoad = inject(toggleNotesLoadKey) as () => void;

const submitText = computed(() =>
    props.noteData ? "Update Note" : "Create Note"
);

const noteForm = ref<InstanceType<typeof VueForm> | null>(null);
const validationSchema = object({
    subject: string().required().label('Title'),
    details: string().required().label('Note Details'),
    shared: boolean().required(),
    urgent: boolean().required(),
});
const initialValues = {
    cust_id: customer.value.cust_id,
    subject: props.noteData ? props.noteData.subject : "",
    details: props.noteData ? props.noteData.details : "",
    shared: props.noteData ? props.noteData.shared : false,
    urgent: props.noteData ? props.noteData.urgent : false,
};

const onSubmit = (form:customerNote) => {
    const formData = useForm(form);
    toggleLoad();

    if (props.noteData) {
        formData.put(route("customers.notes.update", props.noteData.note_id), {
            preserveScroll: true,
            onFinish: () => {
                toggleLoad();
            },
            onSuccess: () => {
                noteForm.value?.endSubmit();
                emit("success");
            },
        });
    } else {
        formData.post(route("customers.notes.store"), {
            only: ["notes", "flash"],
            preserveScroll: true,
            onFinish: () => {
                toggleLoad();
            },
            onSuccess: () => {
                noteForm.value?.endSubmit();
                noteForm.value?.resetForm();
                emit("success");
            },
        });
    }
};
</script>
