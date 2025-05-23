<script setup lang="ts">
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed } from "vue";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();
const props = defineProps<{
    category?: equipmentCategory;
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.category
        ? route("equipment-category.update", props.category.cat_id)
        : route("equipment-category.store")
);
const submitMethod = computed(() => (props.category ? "put" : "post"));
const submitText = computed(() =>
    props.category ? "Update Category Name" : "Create New Category"
);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    name: props.category?.name,
};

const schema = object({
    name: string().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
    >
        <TextInput id="name" name="name" label="Category Name" />
    </VueForm>
</template>
