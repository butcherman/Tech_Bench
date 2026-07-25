<script setup lang="ts">
import { useAppData } from "@/core/state/appData";

const { copyright, version } = useAppData();
</script>

<template>
    <footer
        class="m-0 p-4 h-14 flex justify-between border-t border-t-slate-300 text-muted"
    >
        <div>
            Copyright &copy; {{ copyright }}
            <span class="hidden lg:inline">
                Butcherman - All Rights Reserved
            </span>
        </div>
        <div>
            {{ version }}
        </div>
    </footer>
</template>
