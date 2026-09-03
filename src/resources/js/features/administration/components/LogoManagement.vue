<script setup lang="ts">
import AppLogoForm from "../forms/AppLogoForm.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import verifyModal from "@/core/features/verifyModal/index.js";
import { destroy } from "@/wayfinder/routes/admin/logo/index.js";
import { router } from "@inertiajs/vue3";

defineProps<{
    currentLogo: string;
    isDefault: boolean;
}>();

const onDeleteLogo = () => {
    verifyModal("The system will revert to the Default Logo").then((res) => {
        if (res) {
            router.delete(destroy.url());
        }
    });
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <Card title="Current Logo">
                <div>
                    <img
                        :src="currentLogo"
                        alt="Tech Bench Logo"
                        class="mx-auto"
                    />
                </div>
                <div v-if="!isDefault" class="flex justify-center mt-2">
                    <BaseButton
                        text="Delete Logo"
                        size="sm"
                        variant="danger"
                        icon="trash-alt"
                        @click="onDeleteLogo"
                    />
                </div>
            </Card>
            <Card>
                <AppLogoForm @success="router.reload()" />
            </Card>
        </div>
    </div>
</template>
