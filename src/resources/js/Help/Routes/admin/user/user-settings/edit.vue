<script setup lang="ts">
import UserConfigurationForm from "@/Help/Forms/User/UserConfigurationForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            User Authentication Settings such as Two Factor Authentication and
            Single Sign On via Microsoft Azure are configured here.
        </p>
        <UserConfigurationForm />
    </div>
</template>
