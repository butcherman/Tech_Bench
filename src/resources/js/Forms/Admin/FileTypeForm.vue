<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
    >
        <TextInput
            id="description"
            name="description"
            label="File Type Description"
            focus
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { computed } from "vue";
import { object, string } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    fileType?: customerFileType;
}>();

const submitRoute = computed(() =>
    props.fileType
        ? route("admin.file-types.update", props.fileType.file_type_id)
        : route("admin.file-types.store")
);
const submitMethod = computed(() => (props.fileType ? "put" : "post"));
const submitText = computed(() =>
    props.fileType ? "Update File Type" : "Create File Type"
);

const initValues = {
    description: props.fileType?.description,
};
const schema = object({
    description: string().required(),
});
</script>
