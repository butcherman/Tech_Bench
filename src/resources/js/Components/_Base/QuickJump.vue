<template>
    <div>
        <div id="quick-jump-wrapper">
            <nav
                id="quick-jump-nav"
                class="navbar navbar-expand-lg bg-body-tertiary"
            >
                <div class="container-fluid">
                    <span class="navbar-brand"
                        >{{ title || "Quick Jump" }}:</span
                    >
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#quick-jump-content"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div
                        id="quick-jump-content"
                        class="collapse navbar-collapse"
                    >
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li
                                v-for="item in navList"
                                :key="item.navId"
                                class="nav-item"
                            >
                                <a
                                    class="nav-link pointer"
                                    :class="{
                                        active: item.navId === currentSection,
                                    }"
                                    @click="scrollToElement(item.navId)"
                                >
                                    {{ item.name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <slot />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";

interface navList {
    navId: string;
    name: string;
}

const currentSection = ref<string>();
const sectionList = ref<NodeListOf<HTMLElement>>();

onMounted(() => {
    sectionList.value = document.querySelectorAll("section");
    window.addEventListener("scroll", updateScroll);
});
onUnmounted(() => window.removeEventListener("scroll", updateScroll));

defineProps<{
    navList: navList[];
    title?: string;
}>();

const scrollToElement = (elId: string) => {
    currentSection.value = elId;
    document.getElementById(elId)?.scrollIntoView({ behavior: "smooth" });
};

/**
 * SpyScroll for active element in navbar
 */
const updateScroll = () => {
    sectionList.value?.forEach((sec) => {
        let top = window.scrollY;
        let offset = sec.offsetTop - 150;
        let height = sec.offsetHeight;
        let id = sec.getAttribute("id");

        if (top >= offset && top < offset + height) {
            currentSection.value = id?.toString();
        }
    });
};
</script>

<style lang="scss">
@import "../../../scss/Layouts/appLayout.scss";

#quick-jump-wrapper {
    position: sticky;
    top: $header-height;
    z-index: 100;
}

section {
    scroll-margin-top: calc($header-height + 45px);
}
</style>
