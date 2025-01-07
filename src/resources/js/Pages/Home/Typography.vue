<template>
    <div>
        <h2 class="text-center mb-4">Typography and Component Examples</h2>
        <div class="grid md:grid-cols-3 gap-3">
            <TextComponent />
            <BasicButtons />
            <AdvancedButtons />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import TextComponent from "@/Components/_typography/TextTypography.vue";
import BasicButtons from "@/Components/_typography/BasicButtons.vue";
import AdvancedButtons from "@/Components/_typography/AdvancedButtons.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
