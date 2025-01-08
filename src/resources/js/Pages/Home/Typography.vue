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
        <div class="grid md:grid-cols-2 gap-3">
            <BasicForm />
            <AdvancedForm />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import TextComponent from "@/Components/_typography/TextTypography.vue";
import BasicButtons from "@/Components/_typography/BasicButtons.vue";
import AdvancedButtons from "@/Components/_typography/AdvancedButtons.vue";
import Messages from "@/Components/_typography/Messages.vue";
import DataComponents from "@/Components/_typography/DataComponents.vue";
import BasicForm from "@/Components/_typography/BasicForm.vue";
import AdvancedForm from "@/Components/_typography/AdvancedForm.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
