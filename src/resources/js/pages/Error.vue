<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";
import { computed } from "vue";

// TODO - Properly set this

const props = defineProps<{
    expectedUrl: string;
    status: number;
    message?: string;
}>();

const routeBase = computed(() => {
    let routeParts = props.expectedUrl.split("/");

    return routeParts[0];
});

const authLayout = [
    "about",
    "administration",
    "backups",
    "customers",
    "dashboard",
    "equipment",
    "equipment-category",
    "equipment-data",
    "links",
    "maintenance",
    "reports",
    "tech-tips",
    "user",
];
// const publicLayout = ["onboarding-workbooks", "file-links", "knowledge-base"];

const pageLayout = computed(() => {
    if (authLayout.includes(routeBase.value)) {
        return AppLayout;
    }

    return AuthLayout;
});

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
            500: "/images/error/what.png",
            404: "/images/error/searching.png",
            403: "/images/error/no.png",
            default: "/images/error/oops.png",
        }[props.status];
    }

    return "/images/error/oops.png";
});
</script>

<template>
    <component :is="pageLayout">
        <div class="flex items-center justify-center h-full mx-1 py-2">
            <Card>
                <div>
                    <h1 class="text-danger text-center">Error</h1>
                    <h2 class="my-3 text-center">{{ title }}</h2>
                    <h3 class="text-center">{{ message }}</h3>
                    <div class="flex justify-center">
                        <img :src="image" />
                    </div>
                </div>
            </Card>
        </div>
    </component>
</template>
