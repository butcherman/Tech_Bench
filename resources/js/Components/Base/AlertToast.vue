<template>
    <div ref="toastAlert" class="toast">
        <div
            class="toast-body text-white text-center"
            :class="`bg-${background}`"
        >
            {{ message }}
            <button
                type="button"
                class="btn-close float-end"
                data-bs-dismiss="toast"
            ></button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Toast } from "bootstrap";

defineProps<{
    background: string;
    message: string;
}>();

const toastAlert = ref<HTMLInputElement | null>(null);
let thisAlert: Toast;

onMounted(() => {
    if (toastAlert.value) {
        thisAlert = new Toast(toastAlert?.value, {
            delay: 15000,
        });
        thisAlert.show();
    }
});
</script>
