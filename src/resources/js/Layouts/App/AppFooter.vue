<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>

<template>
    <footer
        id="app-footer"
        class="h-14 p-5 w-full border-t border-t-slate-300 text-muted"
    >
        <span class="float-start">
            Copyright &copy;
            {{ app.copyright }}
            <span class="hidden lg:inline">
                Butcherman - All rights reserved.
            </span>
        </span>
        <span class="float-end">
            {{ app.version }}
        </span>
    </footer>
</template>
