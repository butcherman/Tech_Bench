<template>
    <Teleport to="body">
        <div v-show="isShown" id="modal-wrapper" class="">
            <div
                v-if="!hideBackdrop"
                class="fixed inset-0 bg-gray-500/75 transition-opacity"
            />
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                    >
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            body
                        </div>
                        <div
                            class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                        >
                            <button
                                type="button"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                                @click="buttonClicked"
                            >
                                Footer Button
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, ref } from "vue";

const emit = defineEmits(["ok-clicked", "hide", "hidden"]);
const props = defineProps<{
    message: string;
    hideBackdrop?: boolean;
}>();

const isShown = ref(false);

onMounted(() => (isShown.value = true));

const backdropClicked = () => {
    console.log("backdrop clicked");
};

const buttonClicked = () => {
    console.log("button clicked");
    emit("hidden");
};
</script>

<style scoped>
#modal-wrapper {
    @apply relative z-50;
}
</style>
