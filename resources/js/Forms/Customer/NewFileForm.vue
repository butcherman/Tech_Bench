<template>
  <VueForm
    ref="newFileForm"
    :validation-schema="validationSchema"
    :initial-values="initialValues"
    @submit="onSubmit"
  >
    <TextInput id="name" name="name" label="Name" />
    <SelectInput id="type" name="type" label="File Type" :optionList="fileTypes" />
  </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Components/Base/VueForm.vue";
import TextInput from "@/Components/Base/Input/TextInput.vue";
import SelectInput from "@/Components/Base/Input/SelectInput.vue";
import { ref, reactive, onMounted, inject } from "vue";
import { fileTypesKey } from "@/SymbolKeys/CustomerKeys";

// const props = defineProps<{}>();

const fileTypes = inject(fileTypesKey) as string[];

const validationSchema = {};
const initialValues = {};
const onSubmit = (form) => {
  console.log(form);
};
</script>
