<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, computed } from "vue";

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

const image = computed(() => {
    return {
        503: "/images/error/sleep.png",
        404: "/images/error/searching.png",
        403: "/images/error/no.png",
        default: "/images/error/oops.png",
    }[props.status];
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Head :title="title" />
        <Card class="tb-card text-center">
            <h1 class="text-danger">Error</h1>
            <h2 class="my-3">{{ title }}</h2>
            <div class="flex justify-center">
                <img :src="image" />
            </div>
        </Card>
    </div>
</template>
