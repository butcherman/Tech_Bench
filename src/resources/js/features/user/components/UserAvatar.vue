<script setup lang="ts">
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    user: User;
    size?: ComponentSize;
}>();

const settingsOpen = ref(false);

const componentSize = computed(() => {
    return {
        sm: "w-10 h-10",
        md: "w-20 h-20",
        lg: "w-30 h-30",
    }[props.size ?? "md"];
});

const fontSize = computed(() => {
    return {
        sm: "text-base",
        md: "text-4xl",
        lg: "text-6xl",
    }[props.size ?? "md"];
});

/**
 * Close the settings menu when a link is clicked
 */
router.on("start", () => (settingsOpen.value = false));
</script>

<template>
    <div>
        <div
            class="relative inline-flex items-center justify-center overflow-hidden bg-slate-200 rounded-full"
            :class="[componentSize]"
        >
            <span class="text-body" :class="[fontSize]">
                {{ user.initials }}
            </span>
        </div>
    </div>
</template>
