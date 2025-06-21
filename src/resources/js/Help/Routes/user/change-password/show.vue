<script setup lang="ts">
const title = "Change Password";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>
                Use this page to update your password. The minimum requirements
                for your new password are listed below the password form.
            </p>
        </div>
    </div>
</template>
