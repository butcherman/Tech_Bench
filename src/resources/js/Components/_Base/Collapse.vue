<script setup lang="ts">
import { growShow, shrinkHide } from "@/Composables/animations.module";

defineProps<{
    show: boolean;
    tag?: string;
}>();
</script>

<template>
    <Transition :tag="tag ?? 'div'" @enter="growShow" @leave="shrinkHide">
        <div v-show="show">
            <slot />
        </div>
    </Transition>
</template>
