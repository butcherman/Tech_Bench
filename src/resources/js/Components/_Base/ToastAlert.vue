<script setup lang="ts">
import { gsap } from "gsap";
import { ref } from "vue";

interface toastAlert {
    id: string;
    subject: string;
    message: string;
}

const newToast = ref<toastAlert[]>([]);

const removeToast = (id: string): void => {
    newToast.value = newToast.value.filter((n) => n.id !== id);
};

const pushToast = (toast: toastAlert): void => {
    newToast.value.push(toast);
    setAutoTimeout(toast.id);
};

//  Toast will be auto removed after 15 seconds
const setAutoTimeout = (id: string): void => {
    setTimeout(() => {
        removeToast(id);
    }, 15000);
};

/**
 * Animations
 */
const onEnter = (el: Element): void => {
    gsap.from(el, {
        x: 1000,
    });
};

const onLeave = (el: Element): void => {
    gsap.to(el, {
        x: 1000,
    });
};

defineExpose({ pushToast });
</script>

<template>
    <Teleport to="body">
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <TransitionGroup @enter="onEnter" @leave="onLeave">
                <div
                    v-for="toast in newToast"
                    :key="toast.id"
                    class="toast align-items-center fade show pointer"
                >
                    <div class="toast-header">
                        <strong class="me-auto">{{ toast.subject }}</strong>
                        <button
                            type="button"
                            class="btn-close"
                            @click.stop="removeToast(toast.id)"
                        />
                    </div>
                    <div class="toast-body text-center">
                        {{ toast.message }}
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
