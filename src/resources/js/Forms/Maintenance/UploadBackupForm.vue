<template>
    <VueFileForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('maint.backups.upload')"
        submit-text="Upload Backup"
        @success="$emit('success')"
    />
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import { object } from "yup";

defineEmits(["success"]);

const initValues = {};
const schema = object({});
</script>
