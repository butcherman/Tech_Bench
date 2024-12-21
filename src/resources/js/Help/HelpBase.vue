<template>
    <Card title="Help" class="px-10">
        <HelpComponent />
    </Card>
</template>

<script setup lang="ts">
import Card from "@/Components/Main/Card.vue";
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
