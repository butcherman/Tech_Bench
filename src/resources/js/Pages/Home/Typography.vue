<template>
    <div>
        <h2 class="text-center mb-4">Typography and Component Examples</h2>
        <QuickJump :nav-list="quickJumpList" />
        <Messages id="messages" class="my-3" />
        <div class="grid md:grid-cols-3 gap-3">
            <TextComponent id="typography" />
            <BasicButtons id="basic-buttons" />
            <AdvancedButtons id="advanced-buttons" />
        </div>
        <DataComponents id="data-tables" />
        <div class="grid md:grid-cols-2 gap-3">
            <BasicForm id="basic-forms" />
            <AdvancedForm id="advanced-forms" />
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
import QuickJump from "@/Components/_Base/QuickJump.vue";

const quickJumpList = [
    {
        label: "Messages",
        navId: "messages",
    },
    {
        label: "Typography",
        navId: "typography",
    },
    {
        label: "Basic Buttons",
        navId: "basic-buttons",
    },
    {
        label: "Advanced Buttons",
        navId: "advanced-buttons",
    },
    {
        label: "Data Tables",
        navId: "data-tables",
    },
    {
        label: "Basic Forms",
        navId: "basic-forms",
    },
    {
        label: "Advanced Forms",
        navId: "advanced-forms",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
