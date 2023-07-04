<template>
    <div :id="collapseId" class="bm-collapse" :class="{ 'bm-show': visible }">
        <slot />
    </div>
</template>

<script setup lang="ts">
import { watch } from "vue";
import { gsap } from "gsap";
import { v4 } from "uuid";

const collapseId = v4();
const props = defineProps<{
    visible: boolean;
}>();

const grow = () => {
    gsap.to(document.getElementById(collapseId), {
        display: "block",
        height: "auto",
        opacity: 1,
        duration: 1.2,
    });
};

const shrink = () => {
    gsap.to(document.getElementById(collapseId), {
        display: "none",
        height: 0,
        opacity: 0,
        duration: 0.8,
    });
};

watch(props, (newProps) => {
    if (newProps.visible) {
        grow();
    } else {
        shrink();
    }
});
</script>

<style scoped lang="scss">
.bm-collapse {
    display: none;
    height: 0;
    opacity: 0;
    &.bm-show {
        display: block;
        height: auto;
        opacity: 1;
    }
}
</style>
