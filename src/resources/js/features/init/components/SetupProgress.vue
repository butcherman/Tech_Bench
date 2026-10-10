<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { useSetupState } from "../state/setupState";

const { stepList, getStepUrl } = useSetupState();

const getStepIcon = (step: InitStep): string => {
    if (step.completed) {
        return "check";
    }

    if (step.inProgress) {
        return "circle";
    }

    return "fa-regular fa-circle";
};

const getStepClass = (step: InitStep): string => {
    if (step.completed) {
        return "text-success";
    }

    if (step.inProgress) {
        return "text-warning";
    }

    return "";
};

const goToSetp = (step: InitStep) => {
    if (!step.completed && !step.inProgress) {
        return;
    }

    let url = getStepUrl(step);

    router.get(url);
};
</script>

<template>
    <ol class="flex flex-col gap-4 border-s border-slate-300 ps-6">
        <li
            v-for="step in stepList"
            :key="step.id"
            class="relative flex items-center"
            :class="{ pointer: step.completed || step.inProgress }"
            @click="goToSetp(step)"
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
