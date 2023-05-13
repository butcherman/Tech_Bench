<template>
    <button class="btn btn-sm" title="Refresh" v-tooltip @click="refresh">
        <fa-icon icon="fa-rotate" :spin="loading" />
    </button>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const emit = defineEmits(["start", "end"]);
const props = defineProps<{
    only: string[];
}>();

const loading = ref(false);

const refresh = () => {
    emit("start");
    loading.value = true;

    let only = props.only;
    only.push("flash");

    router.reload({
        only: only,
        preserveScroll: true,
        onFinish: () => {
            emit("end");
            loading.value = false;
        },
    });
};
</script>
