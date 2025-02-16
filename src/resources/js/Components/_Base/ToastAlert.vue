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

<script setup lang="ts">
import { gsap } from "gsap";
import { ref } from "vue";

// const props = defineProps<{}>();

interface toastAlert {
    id: string;
    subject: string;
    message: string;
}

const newToast = ref<toastAlert[]>([]);

const removeToast = (id: string) => {
    newToast.value = newToast.value.filter((n) => n.id !== id);
};

const pushToast = (toast: toastAlert) => {
    newToast.value.push(toast);
    setAutoTimeout(toast.id);
};

//  Toast will be auto removed after 15 seconds
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeToast(id);
    }, 15000);
};

/**
 * Animations
 */
const onEnter = (el: Element) => {
    gsap.from(el, {
        x: 1000,
    });
};

const onLeave = (el: Element) => {
    gsap.to(el, {
        x: 1000,
    });
};

defineExpose({ pushToast });
</script>
