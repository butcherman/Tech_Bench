<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import ErrorMessage from "@/Components/_Base/ErrorMessage.vue";
import LogoImage from "@/Components/LogoImage.vue";

const props = defineProps<{
    status: number;
    message: string;
}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen mx-1 py-2">
        <div>
            <LogoImage>
                <ErrorMessage v-bind="props" class="text-center" hide-image />
            </LogoImage>
        </div>
    </div>
</template>
