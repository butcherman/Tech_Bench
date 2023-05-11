<template>
    <VueForm
        ref="noteForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
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
import { ref, reactive, onMounted, inject } from "vue";
import {
    customerKey,
    allowShareKey,
    toggleNotesLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import { object, string, boolean } from "yup";
import { useForm } from "@inertiajs/vue3";
import type { customerNoteType } from "@/Types";

const emit = defineEmits(["success"]);
const props = defineProps<{
    noteData?: customerNoteType;
}>();

const customer = inject(customerKey) as Ref<customerType>;
const allowShare = inject(allowShareKey) as ComputedRef<boolean>;
const toggleLoad = inject(toggleNotesLoadKey) as () => void;

const noteForm = ref<InstanceType<typeof VueForm>>(null);
const validationSchema = object({
    subject: string().required(),
    details: string().required(),
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

const onSubmit = (form) => {
    console.log(form);

    const formData = useForm(form);
    toggleLoad();

    if (props.noteData) {
        console.log("editing");
    } else {
        console.log("new");

        formData.post(route("customers.notes.store"), {
            only: ["notes", "flash"],
            preserveScroll: true,
            onFinish: () => {
                toggleLoad();
                noteForm.value.endSubmit();
                noteForm.value.resetForm();
            },
            onSuccess: () => {
                emit("success");
            },
        });
    }
};
</script>
