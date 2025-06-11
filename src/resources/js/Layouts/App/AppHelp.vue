<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import HelpLoader from "@/Help/HelpLoader.vue";
import HelpError from "@/Help/HelpError.vue";
import { computed, ref, defineAsyncComponent, useTemplateRef } from "vue";
import { Drawer } from "primevue";
import { router } from "@inertiajs/vue3";
import type { DefineComponent } from "vue";

type helpComponent = {
    getTitle?: () => string;
} & DefineComponent;

const component = useTemplateRef<helpComponent>("help-component");

/**
 * Show/Hide Help page
 */
const showHelp = ref<boolean>(false);

/**
 * Current route user is visiting.
 */
const helpRoute = ref(route().current());

/**
 * Title of the Help Page Drawer Component
 */
const helpTitle = computed<string>(() => {
    if (component.value && typeof component.value.getTitle === "function") {
        return component.value.getTitle();
    }

    return "Help";
});

/**
 * Async Load the Help Component
 */
const HelpComponent = computed(() => {
    // Determine the path of the help file based on the route name
    let helpPath = helpRoute.value?.replace(/\./g, "/");
    // Get a listing of files in the Help File folder
    let resolve = import.meta.glob("../../Help/Routes/**/*.vue");

    console.log(helpPath);

    // If the Component is not found, return the Error Component
    if (!resolve[`../../Help/Routes/${helpPath}.vue`]) {
        return defineAsyncComponent({
            loader: () => import("@/Help/HelpError.vue"),
            loadingComponent: HelpLoader,
            errorComponent: HelpError,
            delay: 200,
            timeout: 3000,
        });
    }

    // Resolve the full path of the Help Component
    let page = resolve[
        `../../Help/Routes/${helpPath}.vue`
    ]() as Promise<DefineComponent>;

    return defineAsyncComponent({
        loader: () => page,
        loadingComponent: HelpLoader,
        errorComponent: HelpError,
        delay: 200,
        timeout: 3000,
    });
});

/**
 * When navigating to a new page, get the route name so the help page can
 * be properly loaded.
 */
router.on("finish", () => {
    helpRoute.value = route().current();
});
</script>

<template>
    <div>
        <BaseBadge
            icon="circle-question"
            variant="help"
            class="p-1!"
            v-tooltip="'Help'"
            @click="showHelp = true"
        />
        <Drawer
            v-model:visible="showHelp"
            class="h-auto!"
            :header="helpTitle"
            position="bottom"
            pt:header:class="border-b"
        >
            <div class="h-full w-full">
                <HelpComponent ref="help-component" />
            </div>
            &nbsp;
        </Drawer>
    </div>
</template>
