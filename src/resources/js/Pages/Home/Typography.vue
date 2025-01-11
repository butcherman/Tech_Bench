<template>
    <div>
        <h2 class="text-center mb-4">Typography and Component Examples</h2>
        <QuickJump :nav-list="quickJumpList" />
        <!-- <Messages id="messages" class="my-3" />
        <div class="grid md:grid-cols-3 gap-3">
            <TextComponent id="typography" />
            <BasicButtons id="basic-buttons" />
            <AdvancedButtons id="advanced-buttons" />
        </div>
        <DataComponents id="data-tables" />
        <Card class="my-3" title="Step Navigation">
            <StepNavigation
                id="step-navigation"
                :step-list="stepList"
                :current-step="1"
            />
        </Card> -->
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
import StepNavigation from "@/Components/_Base/StepNavigation.vue";
import Card from "@/Components/_Base/Card.vue";

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
        label: "Step Navigation",
        navId: "step-navigation",
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

const stepList = [
    {
        id: 1,
        name: "Install Docker",
        icon: "square-binary",
    },
    {
        id: 2,
        name: "Download Tech Bench ",
        icon: "download",
    },
    {
        id: 3,
        name: "Run Docker Compose",
        icon: "person-running",
    },
    {
        id: 4,
        name: "Enjoy",
        icon: "face-smile",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
