<template>
    <div>
        <fa-icon
            icon="fa-circle-question"
            class="mx-1 text-muted my-auto pointer"
            @click="showHelp = true"
        />
        <Drawer
            v-model:visible="showHelp"
            position="bottom"
            header="Help"
            class="h-auto"
        >
            <HelpBase />
        </Drawer>
    </div>
</template>

<script setup lang="ts">
import HelpBase from "@/Help/HelpBase.vue";
import { Drawer } from "primevue";
import { ref } from "vue";

const showHelp = ref(false);
</script>
