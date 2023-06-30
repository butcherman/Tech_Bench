<template>
    <VueForm
        ref="logoForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Upload Logo"
        @submit="onSubmit"
    >
        <DropzoneInput
            paramName="logo"
            ref="uploadInput"
            class="mb-2"
            message="Drag new logo here"
            :accepted-files="['image/*']"
            :multiple="false"
            :max-files="1"
            :upload-url="$route('admin.logo.set')"
            required
            @success="uploadComplete"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import DropzoneInput from "@/Forms/_Base/DropzoneInput.vue";
import { ref } from "vue";
import { object } from "yup";

const emit = defineEmits(['completed']);

const logoForm = ref<InstanceType<typeof VueForm> | null>(null);
const uploadInput = ref<InstanceType<typeof DropzoneInput> | null>(null);
const initValues = {};
const schema = object({});

const onSubmit = () => {
    if (uploadInput.value?.validate()) {
        uploadInput.value.process();
    } else {
        logoForm.value?.endSubmit();
    }
};

const uploadComplete = () => {
    logoForm.value?.endSubmit();
    uploadInput.value?.reset();
    emit('completed');
};
</script>
