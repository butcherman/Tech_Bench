<template>
    <v-bottom-navigation height="50">
        <v-footer class="w-full">
            <div class="w-full text-slate-500">
                <span class="float-start">
                    Copyright &copy;
                    {{ app.copyright }}
                    <span class="d-none d-md-inline">
                        Butcherman - All rights reserved.
                    </span>
                </span>
                <span class="float-end">{{ app.version }}</span>
            </div>
        </v-footer>
    </v-bottom-navigation>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
