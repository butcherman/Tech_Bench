<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import { object } from "yup";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";
import { useAppStore } from "@/Stores/AppStore";
import { v4 as uuidv4 } from "uuid";

const app = useAppStore();
const logoForm = useTemplateRef("logo-form");

const handleSuccess = () => {
    logoForm.value?.resetFileForm();
    router.reload();
    app.pushFlashMsg({
        id: uuidv4(),
        type: "success",
        message: "Logo Saved Successfully",
    });
};

const initValues = {};
const schema = object({});
</script>

<template>
    <VueFileForm
        ref="logo-form"
        submit-text="Upload Logo"
        upload-message="Drag New Logo Here"
        :accepted-files="['image/*']"
        :initial-values="initValues"
        :submit-route="$route('admin.logo.update')"
        :validation-schema="schema"
        file-required
        @success="handleSuccess"
    />
</template>
