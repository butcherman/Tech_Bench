<template>
    <Teleport to="body">
        <div
            ref="verifyModal"
            id="verifyModal"
            class="modal fade"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ title ? title : "Are you sure?" }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        />
                    </div>
                    <div class="modal-body">
                        {{ message }}
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-warning w-25"
                            @click="noClicked"
                        >
                            No
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger w-25"
                            @click="yesClicked"
                        >
                            Yes
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
    title?: string;
    message: string;
}>();

let emit = defineEmits(["yes-clicked", "no-clicked", "hide", "hidden"]);
let verifyModal = ref<HTMLInputElement | null>(null);
let thisModalObj: any;

onMounted(() => {
    if (verifyModal.value) {
        thisModalObj = new Modal(verifyModal.value);
        thisModalObj.show();

        window.addEventListener("hide.bs.modal", () => emit("hide"));
        window.addEventListener("hidden.bs.modal", () => emit("hidden"));
    }
});

onUnmounted(() => {
    window.removeEventListener("hide.bs.modal", () => emit("hide"));
    window.removeEventListener("hidden.bs.modal", () => emit("hidden"));
});

function yesClicked(): void {
    emit("yes-clicked");
    thisModalObj.hide();
}

function noClicked(): void {
    emit("no-clicked");
    thisModalObj.hide();
}
</script>
