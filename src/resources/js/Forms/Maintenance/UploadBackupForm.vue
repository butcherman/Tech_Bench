<template>
    <VueFileForm
        ref="upload-backup-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('maint.backups.upload')"
        submit-text="Upload Backup"
        @success="handleFormSuccess"
    />
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import { useTemplateRef } from "vue";
import { object } from "yup";

const emit = defineEmits(["success"]);

const uploadForm = useTemplateRef("upload-backup-form");

const handleFormSuccess = () => {
    emit("success");
    uploadForm.value?.resetFileForm();
};

const initValues = {};
const schema = object({});
</script>
