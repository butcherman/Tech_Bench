<template>
    <Card class="duration-700 transition-all">
        <div class="flex flex-wrap">
            <h3 class="grow md:grow-0">{{ title ?? "Quick Jump" }}:</h3>
            <div class="md:hidden">
                <BaseButton
                    icon="bars"
                    variant="light"
                    size="small"
                    @click="hideNav = !hideNav"
                />
            </div>
            <div
                id="quick-jump-nav-list"
                class="w-full md:w-fit md:grow md:text-center overflow-hidden md:h-full mt-1"
                :class="{ 'h-0': hideNav }"
            >
                <div class="flex flex-col md:flex-row w-full">
                    <div
                        v-for="(item, index) in navList"
                        class="grow text-muted"
                        :class="{ 'md:border-e': index !== navList.length - 1 }"
                    >
                        <a @click="scrollToElement(item.navId)" class="pointer">
                            {{ item.label }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>

<script setup lang="ts">
import { ref } from "vue";
import Card from "./Card.vue";
import BaseButton from "./Buttons/BaseButton.vue";

interface navList {
    navId: string;
    label: string;
}

defineProps<{
    navList: navList[];
    title?: string;
}>();

const hideNav = ref(true);
const currentSection = ref<string>();

const scrollToElement = (elId: string) => {
    currentSection.value = elId;
    document
        .getElementById(elId)
        ?.scrollIntoView({ behavior: "smooth", block: "center" });
};
</script>

<style scoped>
#quick-jump-nav-list {
    /* transition: max-height;
    transition-duration: 0.5s; */
    transition: all;
    transition-duration: 2s;
}
</style>
