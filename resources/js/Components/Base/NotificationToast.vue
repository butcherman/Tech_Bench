<template>
    <div
        ref="toastNotification"
        class="toast"
    >
        <div class="toast-header">
            <strong class="me-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="toast"
                aria-label="Close"
            ></button>
        </div>
        <div class="toast-body">Hello, world! This is a toast message.</div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Toast } from "bootstrap";

// defineProps<{
//     background: string;
//     message: string;
// }>();

const toastNotification = ref<HTMLInputElement | null>(null);
let thisAlert: Toast;

onMounted(() => {
    if (toastNotification.value) {
        thisAlert = new Toast(toastNotification?.value, {
            delay: 15000,
        });
        thisAlert.show();
    }
});
</script>
