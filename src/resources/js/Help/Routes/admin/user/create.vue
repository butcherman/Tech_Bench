<script setup lang="ts">
import UserForm from "@/Help/Forms/User/UserForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>Create a new user by entering the following information.</p>
        <UserForm />
    </div>
</template>
