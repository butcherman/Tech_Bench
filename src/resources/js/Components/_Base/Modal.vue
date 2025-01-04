<template>
    <Dialog
        v-model:visible="modalOpen"
        :closable="!hideClose"
        modal
        pt:headerActions:class="fixed top-2 right-2"
        :pt:header:class="{ 'border-b': title }"
        :pt:footer:class="{ 'border-t': $slots.footer }"
    >
        <template #header>
            <h4 class="font-bold">
                {{ title }}
            </h4>
        </template>
        <div>
            <slot />
        </div>
        <template #footer>
            <slot name="footer" />
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { Dialog } from "primevue";
import { ref } from "vue";

defineProps<{
    title?: string;
    hideClose?: boolean;
}>();

const modalOpen = ref(false);

const show = () => {
    modalOpen.value = true;
};

const hide = () => {
    modalOpen.value = false;
};

defineExpose({ show, hide });
</script>
