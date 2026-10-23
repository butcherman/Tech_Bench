<script setup lang="ts">
import CertificateForm from "@/Help/Forms/Config/CertificateForm.vue";

const title = "Upload SSL Certificate";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            Use this form to upload a new SSL Certificate.
        </p>
        <p class="text-center">
            Reboot Tech Bench for the certificate to become active.
        </p>
        <CertificateForm />
    </div>
</template>
