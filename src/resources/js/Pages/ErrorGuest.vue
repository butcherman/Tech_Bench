<template>
    <div class="grid grid-cols-1 md:grid-cols-4 items-center h-screen">
        <div class="md:col-span-2 md:col-start-2">
            <LogoImage>
                <div class="w-full text-center">
                    <h1 class="my-3">{{ title }}</h1>
                    <hr />
                    <p>{{ description }}</p>
                </div>
            </LogoImage>
        </div>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
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

const description = computed(() => {
    if (props.message) {
        return props.message;
    }

    return {
        503: "Sorry, we are doing some maintenance behind the curtain. Please check back soon.",
        500: "Whoops, something bad happened.  Our minions are hard at work to determine what went wrong",
        404: "I cannot seem to find the page you are looking for",
        403: "Sorry, you do not appear to belong here",
    }[props.status];
});
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
