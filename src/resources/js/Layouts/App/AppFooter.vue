<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>

<template>
    <footer
        class="m-0 p-4 h-14 flex justify-between border-t border-t-slate-300"
    >
        <div>
            Copyright &copy; {{ app.copyright }}
            <span class="hidden lg:inline">
                Butcherman - All Rights Reserved
            </span>
        </div>
        <div>
            {{ app.version }}
        </div>
    </footer>
</template>
