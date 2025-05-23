<script setup lang="ts">
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";
import { computed } from "vue";

const props = defineProps<{
    tipType?: tipType;
}>();

const submitRoute = computed(() =>
    props.tipType
        ? route("admin.tech-tips.tip-types.update", props.tipType.tip_type_id)
        : route("admin.tech-tips.tip-types.store")
);
const submitMethod = computed(() => (props.tipType ? "put" : "post"));
const submitText = computed(() =>
    props.tipType ? "Edit Tech Tip Type" : "Create Tech Tip Type"
);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    description: props.tipType?.description || "",
};
const schema = object({
    description: string().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput
            id="description"
            name="description"
            label="Tech Tip Type"
            focus
        />
    </VueForm>
</template>
