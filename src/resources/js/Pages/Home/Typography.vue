<template>
    <div>
        <h2 class="text-center mb-4">Typography and Component Examples</h2>
        <Messages class="my-3" />
        <div class="grid md:grid-cols-3 gap-3">
            <TextComponent />
            <BasicButtons />
            <AdvancedButtons />
        </div>
        <DataComponents />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import TextComponent from "@/Components/_typography/TextTypography.vue";
import BasicButtons from "@/Components/_typography/BasicButtons.vue";
import AdvancedButtons from "@/Components/_typography/AdvancedButtons.vue";
import Messages from "@/Components/_typography/Messages.vue";
import Card from "@/Components/_Base/Card.vue";
import DataComponents from "@/Components/_typography/DataComponents.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
