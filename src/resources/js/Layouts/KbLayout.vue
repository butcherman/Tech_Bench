<template>
    <div id="kb-layout-wrapper" class="container-fluid">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <div class="navbar-brand">
                    <img :src="app.logo" :alt="app.name" />
                </div>
                <div class="navbar-text flex-grow-1 d-none d-sm-block">
                    <h2 class="text-center">
                        {{ app.companyName }}
                    </h2>
                </div>
            </div>
        </nav>
        <main id="kb-wrapper">
            <div id="content-wrapper">
                <slot />
            </div>
            <AppFooter />
        </main>
    </div>
</template>

<script setup lang="ts">
import AppFooter from "./AppLayout/AppFooter.vue";
import { useAppStore } from "@/Store/AppStore";
import "../../scss/Layouts/kbLayout.scss";

const app = useAppStore();
</script>
