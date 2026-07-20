<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import Drawer from "@/core/components/Drawer.vue";
import HelpError from "@/help/components/HelpError.vue";
import HelpLoader from "@/help/components/HelpLoader.vue";
import { computed, defineAsyncComponent, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import type { AsyncComponentLoader } from "vue";

const { url } = usePage();

const showHelp = ref<boolean>(false);
const helpBase: string = "../../help/routes";

/**
 * Component to be displayed in the Help Page
 */
const HelpComponent = computed(() => {
    let thisPage: string = `${helpBase}${url}.vue`;
    let resolve = import.meta.glob("../../help/routes/**/*.vue");
    let page = resolve[thisPage];

    if (!page) {
        return HelpError;
    }

    return defineAsyncComponent({
        loader: page as AsyncComponentLoader,
        loadingComponent: HelpLoader,
        errorComponent: HelpError,
        delay: 200,
        timeout: 3000,
    });
});
</script>

<template>
    <div>
        <BaseBadge
            icon="circle-question"
            variant="help"
            class="pointer"
            circle
            @click="showHelp = true"
        />
        <Drawer v-model="showHelp" title="Help">
            <HelpComponent />
        </Drawer>
    </div>
</template>
