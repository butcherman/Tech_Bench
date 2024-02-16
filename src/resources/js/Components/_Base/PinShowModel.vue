<template>
    <div
        id="pin-wrapper"
        class="pointer"
        :class="{ active: active }"
        title="Pin to Navbar"
        v-tooltip
        @click="togglePin"
    >
        <fa-icon icon="thumbtack" />
    </div>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    pinName: string;
    modelName: string;
    modelRoute: string;
    modelKey: string;
    active: boolean;
}>();

const togglePin = () => {
    console.log("toggle pin");
    // isPinned.value = !isPinned.value;

    const formData = useForm(pinData);
    console.log(pinData);
    formData.post(route("user.pinned-links"));
};

const pinData = reactive({
    pin_name: props.pinName,
    model_name: props.modelName,
    model_route: props.modelRoute,
    model_key: props.modelKey,
});
</script>

<style scoped lang="scss">
#pin-wrapper {
    height: 100%;
    transform: rotate(45deg);

    &.active {
        transform: rotate(90deg);
        color: #c21010;
    }
}
</style>
