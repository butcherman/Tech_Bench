<script setup lang="ts">
import VueFileForm from "../_Base/VueFileForm.vue";
import { object } from "yup";

defineEmits<{
    success: [];
}>();

defineProps<{
    link: fileLink;
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {};
const schema = object({});
</script>

<template>
    <VueFileForm
        submit-text="Upload File"
        :initial-values="initValues"
        :submit-route="$route('links.files.store', link.link_id)"
        :validation-schema="schema"
        :max-files="5"
        file-required
        @success="$emit('success')"
    />
</template>
