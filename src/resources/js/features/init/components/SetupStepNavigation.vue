<script setup lang="ts">
import { useSetupState } from "../state/setupState";

const { stepList } = useSetupState();

const getBgClass = (item: SetupStepItem): string => {
    return item.complete ? "bg-green-300" : "bg-white";
};

const goToStep = (stepId: number): void => {
    console.log(stepId);
};
</script>

<template>
    <div class="h-full w-full">
        <ol class="flex w-full">
            <li
                v-for="(item, index) in stepList"
                :key="item.id"
                class="flex items-start"
                :class="{
                    'flex-1': index < stepList.length - 1,
                    pointer: item.complete,
                }"
                @click="goToStep(item.id)"
            >
                <div class="flex flex-col items-center shrink-0">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center"
                        :class="getBgClass(item)"
                    >
                        <fa-icon :icon="item.icon" />
                    </div>
                    <div class="max-w-14 text-center">Step {{ item.id }}</div>
                    <div class="max-w-14 overflow-visible flex justify-center">
                        <span class="text-nowrap shrink-0">
                            {{ item.name }}
                        </span>
                    </div>
                </div>

                <div
                    v-if="index < stepList.length - 1"
                    class="flex-1 h-0.5 mt-4 mx-2"
                    :class="getBgClass(item)"
                ></div>
            </li>
        </ol>
    </div>
</template>
