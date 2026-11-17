<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import EmailManagement from "@/features/admin/components/EmailManagement.vue";

defineOptions({ layout: AppLayout });

defineProps<{
    settings: EmailSettings;
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="border-b border-slate-300">
            <h1>Email Settings</h1>
            <p>
                Modify SMTP settings to allow users to receive emails from the
                application.
            </p>
        </div>
        <EmailManagement :settings />
    </div>
</template>
