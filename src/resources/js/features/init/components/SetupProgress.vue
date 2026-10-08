<script setup lang="ts">
import { useSetupState } from "../state/setupState";

const { activeStep, stepList } = useSetupState();

const getStepIcon = (step: InitStep): string => {
    if (activeStep.value === step.id) {
        return "circle";
    }

    if (step.completed) {
        return "check";
    }

    return "fa-regular fa-circle";
};

const getStepClass = (step: InitStep): string => {
    if (activeStep.value === step.id) {
        return "text-warning";
    }

    if (step.completed) {
        return "text-success";
    }

    return "";
};
</script>

<template>
    <ol class="flex flex-col gap-4 border-s border-slate-300 ps-6">
        <li
            v-for="step in stepList"
            :key="step.id"
            class="relative flex items-center"
        >
            <div class="absolute -left-8.5 bg-white">
                <fa-icon
                    :icon="getStepIcon(step)"
                    :class="getStepClass(step)"
                />
            </div>
            {{ step.name }}
        </li>
    </ol>
</template>
