<script setup lang="ts">
import { useEcho } from "@laravel/echo-vue";
import { ref } from "vue";

const emit = defineEmits<{
    message: [text: string];
}>();

useEcho(
    "administration-channel",
    ".AdministrationEvent",
    (e: AdministrativeMessage) => {
        msgList.value.push(e.msg);
        emit("message", e.msg);
    },
);

const msgList = ref<string[]>([]);
</script>

<template>
    <div class="bg-black text-white h-96 rounded-lg p-1 overflow-auto">
        <div v-for="msg in msgList" :key="msg">
            {{ msg }}
        </div>
    </div>
</template>
