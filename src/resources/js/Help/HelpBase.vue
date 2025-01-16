<template>
    <div class="h-full w-full bg-slate-50">
        <HelpComponent />
    </div>
</template>

<script setup lang="ts">
import HelpError from "@/Help/HelpError.vue";
import HelpLoader from "@/Help/HelpLoader.vue";
import { computed, defineAsyncComponent, onMounted, ref } from "vue";

const currentRoute = ref(route().current());

const HelpComponent = computed(() => {
    return defineAsyncComponent({
        loader: () => import(`./Pages/${currentRoute.value}.vue`),
        loadingComponent: HelpLoader,
        errorComponent: HelpError,
        delay: 200,
        timeout: 3000,
    });
});

onMounted(() => console.log(currentRoute.value));
</script>
