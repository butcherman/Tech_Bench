<template>
    <Teleport to="body">
        <div ref="myModal" class="modal fade" tabindex="-1">
            <div
                class="modal-dialog"
                :class="{
                    'modal-lg': size === 'lg',
                    'modal-xl': size === 'xl',
                    'modal-sm': size === 'sm',
                }"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <slot name="header">
                            <h5 class="modal-title">{{ title }}</h5>
                        </slot>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            title="Close"
                            v-tooltip
                        />
                    </div>
                    <div class="modal-body">
                        <slot name="default" />
                    </div>
                    <div class="modal-footer">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { onMounted, onBeforeUnmount, ref } from "vue";
import { Modal } from "bootstrap";

interface TB_Modal extends Modal {
    _config?: {
        backdrop: "static" | boolean;
    };
}

const props = defineProps<{
    title?: string;
    size?: "sm" | "lg" | "xl";
    noCloseOnClick?: boolean;
}>();

const emit = defineEmits(["hide", "hidden", "show", "shown", "hidePrevented"]);

const myModal = ref<HTMLInputElement | null>(null);
const preventClosing = ref<boolean>(false);
let thisModalObj: TB_Modal;

onMounted(() => {
    if (myModal.value) {
        thisModalObj = new Modal(myModal.value, {
            backdrop: props.noCloseOnClick ? "static" : true,
        });

        myModal.value.addEventListener("show.bs.modal", () => emit("show"));
        myModal.value.addEventListener("shown.bs.modal", () => emit("shown"));
        myModal.value.addEventListener("hide.bs.modal", (e) => {
            if (preventClosing.value) {
                e.preventDefault();
                emit("hidePrevented");
            } else {
                emit("hide");
            }
        });
        myModal.value.addEventListener("hidden.bs.modal", () => emit("hidden"));
        myModal.value.addEventListener("hidePrevented.bs.modal", () =>
            emit("hidePrevented")
        );
    }
});

onBeforeUnmount(() => {
    thisModalObj.hide();
    myModal.value?.removeEventListener("show.bs.modal", () => emit("show"));
    myModal.value?.removeEventListener("shown.bs.modal", () => emit("shown"));
    myModal.value?.addEventListener("hide.bs.modal", () => emit("hide"));
    myModal.value?.removeEventListener("hidden.bs.modal", () => emit("hidden"));
    myModal.value?.removeEventListener("hidePrevented.bs.modal", () =>
        emit("hidePrevented")
    );
});

function stopClose() {
    if (thisModalObj._config !== undefined) {
        thisModalObj._config.backdrop = "static";
        preventClosing.value = true;
    }
}

function enableClose() {
    if (thisModalObj._config !== undefined) {
        thisModalObj._config.backdrop = true;
        preventClosing.value = false;
    }
}

function show(): void {
    thisModalObj.show();
}

function hide(): void {
    thisModalObj.hide();
}

defineExpose({ show, hide, stopClose, enableClose });
</script>
