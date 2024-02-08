<template>
    <Modal
        title="Help"
        ref="helpModal"
        size="xl"
        @shown="isShown = true"
        @hidden="isShown = false"
    >
        <HelpComponent v-if="isShown" :key="app.currentRoute" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import HelpLoader from "./HelpLoader.vue";
import HelpError from "./HelpError.vue";
import { ref, computed, defineAsyncComponent } from "vue";
import { useAppStore } from "@/Store/AppStore";

const isShown = ref(false);
const app = useAppStore();

const helpModal = ref<InstanceType<typeof Modal> | null>(null);
const HelpComponent = computed(() => {
    const curRoute = app.currentRoute;

    return defineAsyncComponent({
        loader: () => import(`./Components/${curRoute}.vue`),
        loadingComponent: HelpLoader,
        errorComponent: HelpError,
        delay: 200,
        timeout: 3000,
    });
});

const show = () => {
    console.log(app.currentRoute);
    helpModal.value?.show();
};

defineExpose({ show });
</script>
