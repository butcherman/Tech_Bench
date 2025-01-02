<template>
    <div v-if="app.flash.length">
        <Message
            v-for="msg in app.flash"
            :severity="msg.type === 'status' ? 'info' : msg.type"
        >
            {{ msg.message }}
        </Message>
    </div>
</template>

<script setup lang="ts">
import { Message } from "primevue";
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
