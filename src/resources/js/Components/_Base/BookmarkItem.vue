<template>
    <span v-if="loading" class="spinner-grow spinner-grow-sm" />
    <span
        v-else
        class="pointer"
        :class="bookmarkClass"
        :title="bookmarkTitle"
        v-tooltip
        @click="toggleBookmark"
    >
        <fa-icon :icon="bookmarkIcon" />
    </span>
</template>

<script setup lang="ts">
import okModal from "@/Modules/okModal";
import axios from "axios";
import { ref, computed } from "vue";

const props = defineProps<{
    isBookmark: boolean;
    toggleRoute: string;
}>();

const isActive = ref<boolean>(props.isBookmark);
const loading = ref<boolean>(false);

const bookmarkIcon = computed<string>(() => {
    return isActive.value ? "fa-solid fa-bookmark" : "fa-regular fa-bookmark";
});
const bookmarkClass = computed<string>(() => {
    return isActive.value ? "bookmark-checked" : "bookmark-unchecked";
});
const bookmarkTitle = computed<string>(() => {
    return isActive.value ? "Remove From Bookmarks" : "Add to Bookmarks";
});

const toggleBookmark = (): void => {
    loading.value = true;

    axios
        .post(props.toggleRoute, { value: !isActive.value })
        .then((res) => {
            if (res.data.success) {
                isActive.value = !isActive.value;
            }
        })
        .catch((err) => {
            okModal(err);
        })
        .finally(() => {
            loading.value = false;
        });
};
</script>

<style scoped lang="scss">
.bookmark-checked {
    color: #d46e70;
}

.bookmark-unchecked {
    color: #c4c4c4;
}
</style>
