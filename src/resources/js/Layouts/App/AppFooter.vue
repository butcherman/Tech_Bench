<template>
    <footer id="app-footer">
        <span class="float-start">
            Copyright &copy;
            {{ app.copyright }}
            <span class="-md:hidden"> Butcherman - All rights reserved. </span>
        </span>
        <span class="float-end">
            {{ app.version }}
        </span>
    </footer>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
