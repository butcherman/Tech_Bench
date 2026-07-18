<script setup lang="ts">
import LoginInstructions from "@/features/auth/components/LoginInstructions.vue";
import LogoImage from "@/features/auth/components/LogoImage.vue";
import AuthLayout from "@/layouts/authLayout/AuthLayout.vue";
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex flex-col md:flex-row h-screen">
        <div class="flex md:grow items-center justify-center">
            <div class="md:w-80 m-3">
                <LogoImage />
            </div>
        </div>
        <div
            class="flex items-center md:basis-80 bg-white m-3 md:m-0 rounded-lg md:rounded-none"
        >
            <div class="m-3 w-full">
                <LoginInstructions />
            </div>
        </div>
    </div>
</template>
