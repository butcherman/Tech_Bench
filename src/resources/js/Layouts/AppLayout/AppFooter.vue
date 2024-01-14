<template>
    <footer id="app-footer">
        <div
            class="d-sm-flex justify-content-center justify-content-sm-between"
        >
            <span
                class="text-muted text-center text-sm-left d-block d-sm-inline-block"
            >
                Copyright &copy;
                {{ app.copyright }}
                <span class="d-none d-md-inline">
                    Butcherman - All rights reserved.
                </span>
            </span>
            <span
                class="text-muted float-none float-sm-end d-block mt-1 mt-sm-0 text-center"
            >
                {{ app.version }}
            </span>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Store/AppStore";

const app = useAppStore();
</script>
