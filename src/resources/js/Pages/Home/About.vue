<template>
    <div class="flex justify-center">
        <Card class="md:w-1/2">
            <img
                src="/images/TechBenchLogo.png"
                class="mx-auto d-block img-fluid"
                alt="Tech Bench"
            />
            <p class="text-center">
                Tech Bench &copy; {{ app.copyright }}
                <span class="d-inline-block">
                    Butcherman - All Rights Reserved
                </span>
            </p>
            <p class="text-center">{{ app.version }} (Build {{ build }})</p>
            <p class="text-center">Build Date - {{ build_date }}</p>
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import { useAppStore } from "@/Stores/AppStore";
import Card from "@/Components/_Base/Card.vue";

defineProps<{
    build: string;
    build_date: string;
}>();
const app = useAppStore();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
