<script setup lang="ts">
import { growShow, shrinkHide } from "@/Composables/animations.module";

defineProps<{
    show: boolean;
}>();

// TODO - Refactor????
</script>

<template>
    <div>
        <Transition @enter="growShow" @leave="shrinkHide">
            <div v-show="show">
                <slot />
            </div>
        </Transition>
    </div>
</template>
