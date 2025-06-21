<script setup lang="ts">
defineEmits<{
    "navigate-to": [stepId: number];
}>();

defineProps<{
    currentStep: number;
    hideId?: boolean;
    stepList: {
        id: number;
        name: string;
        icon: string;
    }[];
}>();
</script>

<template>
    <ol class="step-indicator">
        <template v-for="step in stepList" :key="step.id">
            <li
                :class="{
                    active: step.id === currentStep,
                    complete: step.id < currentStep,
                }"
                @click="$emit('navigate-to', step.id)"
            >
                <div class="step">
                    <fa-icon :icon="step.icon" />
                </div>
                <div class="caption hidden-sm">
                    <span v-if="!hideId"> Step {{ step.id }} - </span>
                    {{ step.name }}
                </div>
            </li>
        </template>
    </ol>
</template>

<style scoped>
.step-indicator {
    margin: 0;
    overflow: auto;
    padding: 0;
    text-align: center;

    li {
        display: table-cell;
        position: relative;
        float: none;
        padding: 0;
        width: 1%;

        &:after {
            background-color: #ccc;
            content: "";
            display: block;
            height: 1px;
            position: absolute;
            width: 100%;
            top: 32px;
            left: 50%;
        }

        &:last-child:after {
            display: none;
        }

        &.active .step {
            border-color: #4183d7;
            color: #4183d7;
        }

        &.active .caption {
            color: #4183d7;
        }

        &.complete:after {
            background-color: #87d37c;
        }
    }

    .complete .step {
        border-color: #87d37c;
        color: #87d37c;
    }

    .complete .caption {
        color: #87d37c;
    }

    .step {
        background-color: #fff;
        border-radius: 50%;
        border: 1px solid #ccc;
        color: #ccc;
        font-size: 24px;
        height: 64px;
        line-height: 64px;
        margin: 0 auto;
        position: relative;
        width: 64px;
        z-index: 1;
    }

    .step:hover {
        cursor: pointer;
    }

    .caption {
        color: #ccc;
        padding: 11px 16px;
    }
}
</style>
