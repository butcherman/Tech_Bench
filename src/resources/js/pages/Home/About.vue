<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import LogoImage from "@/core/components/LogoImage.vue";
import { useAppData } from "@/core/state/appData";

const { copyright, version, appName } = useAppData();

const props = defineProps<{
    build: string;
    build_date: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex grow justify-center items-center">
        <Card>
            <h2 class="text-center">{{ appName }}</h2>
            <LogoImage />
            <hr class="my-4" />
            <p class="text-center">
                Tech Bench &copy; {{ copyright }}
                <span class="inline-block">
                    Butcherman - All Rights Reserved
                </span>
            </p>
            <p class="text-center">{{ version }} (Build {{ build }})</p>
            <p class="text-center">Build Date - {{ build_date }}</p>
        </Card>
    </div>
</template>
