<template>
    <Modal
        title="Help"
        ref="helpModal"
        size="xl"
        @shown="isShown = true"
        @hidden="isShown = false"
    >
        <HelpComponent v-if="isShown" :key="curRoute" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import HelpLoader from "./HelpLoader.vue";
import HelpError from "./HelpError.vue";
import { ref, computed, defineAsyncComponent } from "vue";

const isShown = ref(false);
const curRoute = ref(route().current());

const helpModal = ref<InstanceType<typeof Modal> | null>(null);
const HelpComponent = computed(() => {
    return defineAsyncComponent({
        loader: () => import(`./Pages/${curRoute.value}.vue`),
        loadingComponent: HelpLoader,
        errorComponent: HelpError,
        delay: 200,
        timeout: 3000,
    });
});

const show = () => {
    helpModal.value?.show();
};

defineExpose({ show });
</script>
