<script setup lang="ts">
const title = "Certificate Settings";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            This page shows information about the SSL Certificate loaded into
            the system.
        </p>
        <p>
            Use the links at the bottom of the page to import a new certificate,
            generate a CSR Request, or delete the existing certificate.
        </p>
        <p>
            <strong>Note:</strong> Any changes to the certificate require a
            reboot.
        </p>
    </div>
</template>
