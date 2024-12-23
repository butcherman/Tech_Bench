<template>
    <div>
        <p class="text-center">
            The following devices have been verified and saved as trusted
            devices.
        </p>
        <p class="text-center">
            A trusted device allows you to skip the two-factor authentication
            for 180 days from the registration date.
        </p>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    user: user;
    devices: userDevice[];
}>();
</script>
