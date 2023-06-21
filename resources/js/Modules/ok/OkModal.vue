<template>
    <Teleport to="body">
        <div ref="okModal" id="okModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
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
    </Teleport>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import { Modal } from "bootstrap";

defineProps<{
    message: string;
}>();

let emit = defineEmits(["hide", "hidden"]);
let okModal = ref<HTMLInputElement | null>(null);
let thisModalObj: any;

onMounted(() => {
    if (okModal.value) {
        thisModalObj = new Modal(okModal.value);
        thisModalObj.show();

        window.addEventListener("hide.bs.modal", () => emit("hide"));
        window.addEventListener("hidden.bs.modal", () => emit("hidden"));
    }
});

onUnmounted(() => {
    window.removeEventListener("hide.bs.modal", () => emit("hide"));
    window.removeEventListener("hidden.bs.modal", () => emit("hidden"));
});

function okClicked(): void {
    emit("ok-clicked");
    thisModalObj.hide();
}
</script>
