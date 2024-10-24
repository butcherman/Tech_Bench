<template>
    <button
        type="button"
        class="btn btn-sm"
        title="Refresh"
        v-tooltip
        @click="refresh"
    >
        <fa-icon icon="fa-rotate" :spin="loading" />
    </button>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const emit = defineEmits(["loading-start", "loading-complete"]);
const props = defineProps<{
    only: string[];
}>();

const loading = ref<boolean>(false);

const refresh = (): void => {
    emit("loading-start");
    loading.value = true;

    let only: string[] = props.only;
    only.push("flash");

    router.reload({
        only: only,
        onFinish: () => {
            emit("loading-complete");
            loading.value = false;
        },
    });
};

defineExpose({ refresh });
</script>
