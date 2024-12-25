<template>
    <v-timeline
        direction="horizontal"
        side="end"
        truncate-line="both"
        density="compact"
    >
        <template v-for="step in stepList">
            <v-timeline-item
                :icon="step.icon"
                class="text-center"
                :dot-color="getIconColor(step.id)"
            >
                {{ step.name }}
            </v-timeline-item>
        </template>
    </v-timeline>
</template>

<script setup lang="ts">
const props = defineProps<{
    stepList: {
        id: number;
        name: string;
        icon: string;
    }[];
    currentStep: number;
}>();

const getIconColor = (step: number) => {
    if (step === props.currentStep) {
        return "blue";
    }

    if (step < props.currentStep) {
        return "green";
    }

    return "white";
};
</script>
