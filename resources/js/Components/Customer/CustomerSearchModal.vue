<template>
    <Teleport to="body">
        <div
            ref="myModal"
            id="myModal"
            class="modal fade"
            tabindex="-1"
            aria-labelledby=""
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search for Customer</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        />
                    </div>
                    <div class="modal-body">
                        <CustomerBasicSearch
                            :initial-search="initialSearch"
                            @selected="selectCust"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import CustomerBasicSearch from "./CustomerBasicSearch.vue";
import { Modal } from "bootstrap";
import { onMounted, onUnmounted, ref } from "vue";

const emit = defineEmits(["hide", "hidden", "selected"]);
defineProps<{
    initialSearch?: string;
}>();

let thisModalObj: Modal;
const myModal = ref<HTMLInputElement | null>(null);

onMounted(() => {
    window.addEventListener("hide.bs.modal", () => emit("hide"));
    window.addEventListener("hidden.bs.modal", () => emit("hidden"));

    if (myModal.value) {
        thisModalObj = new Modal(myModal.value);
    }

    thisModalObj.show();
});

onUnmounted(() => {
    window.removeEventListener("hide.bs.modal", () => emit("hide"));
    window.removeEventListener("hidden.bs.modal", () => emit("hidden"));
});

const selectCust = (cust: customer) => {
    emit("selected", cust);
    thisModalObj.hide();
};
</script>
