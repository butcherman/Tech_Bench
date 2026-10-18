<script setup lang="ts">
import { useAnimationHelper } from "../composables/animationHelper";

const props = defineProps<{
    show: boolean;
}>();

const { growShow, shrinkHide } = useAnimationHelper();
</script>

<template>
    <Transition name="collapse" @enter="growShow" @leave="shrinkHide">
        <div v-show="show">
            <slot />
        </div>
    </Transition>
</template>
