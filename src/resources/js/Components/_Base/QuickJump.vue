<script setup lang="ts">
import BaseButton from "./Buttons/BaseButton.vue";
import Card from "./Card.vue";
import { ref } from "vue";
import { Drawer } from "primevue";

/*
|-------------------------------------------------------------------------------
| Local Types
|-------------------------------------------------------------------------------
*/
interface navList {
    navId: string;
    label: string;
}

defineProps<{
    navList: navList[];
    title?: string;
}>();

const showNav = ref<boolean>(false);
const currentSection = ref<string>();

/**
 * Place the selected element at the center of the screen.
 */
const scrollToElement = (elId: string): void => {
    currentSection.value = elId;
    document
        .getElementById(elId)
        ?.scrollIntoView({ behavior: "smooth", block: "center" });

    if (showNav.value) {
        showNav.value = false;
    }
};
</script>

<template>
    <Card class="duration-700 transition-all">
        <div class="flex flex-row">
            <div class="h-full grow md:grow-0">
                <h3>{{ title ?? "Quick Jump" }}:</h3>
            </div>
            <div class="md:hidden">
                <BaseButton
                    icon="bars"
                    variant="light"
                    size="small"
                    @click="showNav = !showNav"
                />
            </div>
            <div
                id="quick-jump-nav-list"
                class="w-0 md:w-auto overflow-hidden h-0 md:h-auto md:grow md:ms-2 flex flex-wrap text-center"
            >
                <div
                    v-for="(item, index) in navList"
                    class="grow text-muted mx-2 mt-1"
                    :class="{ 'md:border-e': index !== navList.length - 1 }"
                >
                    <a @click="scrollToElement(item.navId)" class="pointer">
                        {{ item.label }}
                    </a>
                </div>
            </div>
        </div>
        <Drawer v-model:visible="showNav" header="Quick Jump" position="top">
            <div
                v-for="(item, index) in navList"
                class="grow text-muted mx-2 mt-1"
                :class="{ 'md:border-e': index !== navList.length - 1 }"
            >
                <a @click="scrollToElement(item.navId)" class="pointer">
                    {{ item.label }}
                </a>
            </div>
        </Drawer>
    </Card>
</template>
