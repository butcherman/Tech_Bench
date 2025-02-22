<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import LogoImage from "@/Components/LogoImage.vue";
import { computed } from "vue";

const props = defineProps<{
    status: number;
    message: string;
}>();

const title = computed(() => {
    return {
        503: "503 | Service Unavailable",
        500: "500 | Server Error",
        404: "404 | Page Not Found",
        403: "403 | Forbidden",
        429: "429 | Too Many Requests",
    }[props.status];
});
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>

<template>
    <div class="flex items-center justify-center h-screen mx-1 py-2">
        <div>
            <LogoImage>
                <div class="w-full text-center">
                    <h1 class="text-danger">Error</h1>
                    <h2 class="my-3">{{ title }}</h2>
                </div>
            </LogoImage>
        </div>
    </div>
</template>
