<script setup lang="ts">
import AuthLayout from "@/layouts/authLayout/AuthLayout.vue";
import LoginInstructions from "@/features/auth/components/LoginInstructions.vue";
import LoginLogo from "@/features/auth/components/LoginLogo.vue";

const props = defineProps<{
    welcomeMessage?: string;
    homeLinks: linkList[];
    publicLink: linkList | false;
    allowOath: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex flex-col md:flex-row h-screen">
        <div class="flex md:grow items-center justify-center">
            <div class="md:w-80 m-3">
                <LoginLogo v-bind="props" />
            </div>
        </div>
        <div
            class="flex items-center md:basis-80 bg-white m-3 md:m-0 rounded-lg md:rounded-none"
        >
            <div class="m-3 w-full">
                <LoginInstructions :allow-oath="allowOath" />
            </div>
        </div>
    </div>
</template>
