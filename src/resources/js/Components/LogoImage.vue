<script setup lang="ts">
import Card from "./_Base/Card.vue";
import { useAppStore } from "@/Stores/AppStore";

defineProps<{
    darkHeader?: boolean;
}>();

const app = useAppStore();
</script>

<template>
    <div>
        <h1
            class="text-center text-white"
            :class="{ '!text-black': darkHeader }"
        >
            {{ app.name }}
        </h1>
        <Card>
            <img :src="app.logo" class="justify-self-center" />
            <slot />
        </Card>
    </div>
</template>
