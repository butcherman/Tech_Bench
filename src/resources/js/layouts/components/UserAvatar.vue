<script setup lang="ts">
import { useUserAuth } from "@/core/state/userAuth";

const { user } = useUserAuth();
</script>

<template>
    <div>
        <div
            class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-slate-200 rounded-full pointer"
        >
            <span class="font-medium text-body">{{ user.initials }}</span>
        </div>
    </div>
</template>
