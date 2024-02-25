<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput id="name" name="name" label="Category Name" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { computed } from "vue";
import { object, string } from "yup";

const props = defineProps<{
    category: categoryList | null;
}>();

const submitRoute = computed(() =>
    props.category
        ? route("equipment-category.update", props.category.cat_id)
        : route("equipment-category.store")
);
const submitMethod = computed(() => (props.category ? "put" : "post"));
const submitText = computed(() =>
    props.category ? "Update Category Name" : "Create New Category"
);

const initValues = {
    name: props.category?.name,
};
const schema = object({
    name: string().required(),
});
</script>
