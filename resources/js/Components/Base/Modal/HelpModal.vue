<template>
    <div ref="okModal" id="okModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    />
                </div>
                <div v-if="message" class="modal-body text-center">
                    {{ message }}
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-info w-25"
                        @click="okClicked"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import { Modal } from "bootstrap";

defineProps<{
    title: string;
    message?: string;
}>();

let emit = defineEmits(["ok-clicked", "ok-hide", "ok-hidden"]);
let helpModal = ref<HTMLInputElement | null>(null);
let thisModalObj: Modal;

onMounted(() => {
    if (helpModal.value) {
        thisModalObj = new Modal(helpModal.value);
        thisModalObj.show();
    }

    window.addEventListener("hide.bs.modal", () => emit("ok-hide"));
    window.addEventListener("hidden.bs.modal", () => emit("ok-hidden"));
});

onUnmounted(() => {
    window.removeEventListener("hide.bs.modal", () => emit("ok-hide"));
    window.removeEventListener("hidden.bs.modal", () => emit("ok-hidden"));
});

function okClicked(): void {
    emit("ok-clicked");
    thisModalObj.hide();
}
</script>
