<script setup lang="ts">
import PasswordPolicyForm from "@/Help/Forms/User/PasswordPolicyForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            Modify the minimum password requirements on this page
        </p>
        <PasswordPolicyForm />
    </div>
</template>
