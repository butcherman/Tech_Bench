<template>
    <div :id="collapseId" :class="initialClass">
        <slot />
    </div>
</template>

<script setup lang="ts">
// TODO - Use Transition to clean up animation
import { watch, onMounted, ref } from "vue";
import { gsap } from "gsap";
import { v4 } from "uuid";

const collapseId = v4();
const props = defineProps<{
    visible: boolean;
}>();
const initialClass = ref("bm-collapse");

onMounted(
    () => (initialClass.value = props.visible ? "bm-collapse" : "bm-hide")
);

const grow = () => {
    let timeline = gsap.timeline();
    timeline.to(document.getElementById(collapseId), {
        display: "block",
        duration: 0.1,
    });
    timeline.to(document.getElementById(collapseId), {
        height: "auto",
        duration: 0.5,
    });
    timeline.to(document.getElementById(collapseId), {
        opacity: 1,
        duration: 1,
    });
};

const shrink = () => {
    let timeline = gsap.timeline();
    timeline.to(document.getElementById(collapseId), {
        opacity: 0,
        duration: 1,
    });
    timeline.to(document.getElementById(collapseId), {
        height: 0,
        duration: 0.5,
    });
    timeline.to(document.getElementById(collapseId), {
        display: "none",
        duration: 0.1,
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
    display: block;
    height: auto;
    opacity: 1;
}

.bm-hide {
    display: none;
    height: 0;
    opacity: 0;
}
</style>
