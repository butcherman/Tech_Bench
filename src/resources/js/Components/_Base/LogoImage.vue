<template>
    <div>
        <h1 class="text-center text-white">
            {{ app.name }}
        </h1>
        <!-- <div class="m-2 p-4 bg-white rounded"> -->
        <Card>
            <img :src="app.logo" class="justify-self-center" />
            <slot />
        </Card>
        <!-- </div> -->
    </div>
</template>

<script setup lang="ts">
import Card from "./Card.vue";
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
