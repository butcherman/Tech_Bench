<template>
    <VueForm
        ref="tipTypeForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="handleSuccess"
    >
        <TextInput
            id="description"
            name="description"
            label="Tech Tip Type"
            focus
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { computed, ref } from "vue";
import { object, string } from "yup";

const emit = defineEmits(["success"]);
const props = defineProps<{
    tipType?: tipType;
}>();

const tipTypeForm = ref<InstanceType<typeof VueForm> | null>(null);

const submitRoute = computed(() =>
    props.tipType
        ? route("admin.tech-tips.tip-types.update", props.tipType.tip_type_id)
        : route("admin.tech-tips.tip-types.store")
);
const submitMethod = computed(() => (props.tipType ? "put" : "post"));
const submitText = computed(() =>
    props.tipType ? "Edit Tech Tip Type" : "Create Tech Tip Type"
);

const initValues = {
    description: props.tipType?.description || "",
};
const schema = object({
    description: string().required(),
});

const handleSuccess = () => {
    tipTypeForm.value?.resetForm();
    emit("success");
};
</script>
