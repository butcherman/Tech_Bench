<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { useAppData } from "@/core/state/appData";

defineOptions({ layout: AppLayout });

const props = defineProps<{
    required: boolean;
    methods: {
        name: MultiFactorMethod;
        setupRoute: string;
    }[];
}>();

const { appName } = useAppData();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-4 mb-4">
            <h1>Secure Your Account</h1>
            <p v-if="required">
                Multi-Factor Authentication is required to use the {{ appName }}
            </p>
            <p>
                Please select one of the options below to complete the MFA Setup
                Process.
            </p>
        </div>
        <div class="flex gap-2 justify-center">
            <div v-for="method in methods" class="basis-sm">
                <BaseButton
                    :text="method.name"
                    :href="method.setupRoute"
                    variant="info"
                    class="w-full"
                />
            </div>
        </div>
    </div>
</template>
