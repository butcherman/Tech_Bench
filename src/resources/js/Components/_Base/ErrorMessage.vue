<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    status?: number;
    message: string;
    hideImage?: boolean;
}>();

const title = computed(() => {
    if (props.status) {
        return {
            503: "503 | Service Unavailable",
            500: "500 | Server Error",
            404: "404 | Page Not Found",
            403: "403 | Forbidden",
            429: "429 | Too Many Requests",
        }[props.status];
    }

    return 599;
});

const image = computed(() => {
    if (props.status) {
        return {
            503: "/images/error/sleep.png",
            404: "/images/error/searching.png",
            403: "/images/error/no.png",
            default: "/images/error/oops.png",
        }[props.status];
    }

    return "/images/error/oops.png";
});
</script>

<template>
    <div>
        <h1 class="text-danger">Error</h1>
        <h2 class="my-3">{{ title }}</h2>
        <div v-if="!hideImage" class="flex justify-center">
            <img :src="image" />
        </div>
    </div>
</template>
