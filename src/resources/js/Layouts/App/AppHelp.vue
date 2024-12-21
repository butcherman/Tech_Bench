<template>
    <v-bottom-sheet>
        <template #activator="{ props }">
            <font-awesome-icon
                v-bind="props"
                icon="circle-question"
                class="m-1 text-muted pointer"
            />
        </template>
        <HelpBase />
    </v-bottom-sheet>
</template>

<script setup lang="ts">
import HelpBase from "@/Help/HelpBase.vue";
</script>
