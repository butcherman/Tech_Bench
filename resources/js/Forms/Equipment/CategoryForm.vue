<template>
    <VueForm
        ref="categoryForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-text="submitText"
        @submit="onSubmit"
    >
        <TextInput id="name" name="name" label="Category Name" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { object, string } from "yup";

const categoryForm = ref<InstanceType<typeof VueForm> | null>(null);
const isNew = ref(true);
const editValue = ref<categoryList | null>(null);
const emit = defineEmits(["success"]);
const initValues = {
    name: "",
};
const schema = object({
    name: string().required(),
});

const submitText = computed(() =>
    isNew.value ? "Create New Category" : "Update Category Name"
);

const onSubmit = (form: { name: string }) => {
    const formData = useForm(form);

    if (isNew.value) {
        formData.post(route("equipment-category.store"), {
            onFinish: () => categoryForm.value?.endSubmit(),
            onSuccess: () => {
                categoryForm.value?.resetForm();
                emit("success");
            },
        });
    } else {
        formData.put(
            route("equipment-category.update", editValue.value?.cat_id),
            {
                onFinish: () => categoryForm.value?.endSubmit(),
                onSuccess: () => {
                    categoryForm.value?.resetForm();
                    emit("success");
                },
            }
        );
    }
};

/**
 * Present a blank form for new entry
 */
const createNew = () => {
    categoryForm.value?.setFieldValue("name", "");
    isNew.value = true;
};

/**
 * Populate form with data for editing existing entry
 */
const editCat = (cat: categoryList) => {
    editValue.value = cat;
    isNew.value = false;
    categoryForm.value?.setFieldValue("name", cat.name);
};

defineExpose({ createNew, editCat });
</script>
