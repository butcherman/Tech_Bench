<template>
    <div>
        <p>Hello {{ app.user?.full_name }},</p>
        <p>A new Tech Tip has been created.</p>
        <h4 class="text-center my-2">{{ tip_data.subject }}</h4>
        <p class="text-center">
            <Link
                :href="$route('tech-tips.show', tip_data.slug)"
                class="btn btn-info"
            >
                Click Here to View this Tech Tip
            </Link>
        </p>
    </div>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Store/AppStore";

defineProps<{
    tip_data: techTip;
}>();

const app = useAppStore();
</script>
