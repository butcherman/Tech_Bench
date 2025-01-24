<template>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <Card title="Current Logo">
                <div>
                    <img
                        id="app-logo-current"
                        :src="logo"
                        alt="Tech Bench Logo"
                        class="mx-auto"
                    />
                </div>
            </Card>
            <Card title="New Logo">
                <LogoForm />
            </Card>
        </div>
        <div class="text-center mt-3">
            <DeleteButton
                text="Delete Logo"
                class="w-1/2"
                confirm-msg="The system will revert to the Default Logo"
                confirm
                @accepted="deleteLogo"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import Card from "@/Components/_Base/Card.vue";
import LogoForm from "@/Forms/Admin/Config/LogoForm.vue";
import { computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";

const logo = computed(() => usePage<pageProps>().props.app.logo);

/**
 * Delete logo after confirmation
 */
const deleteLogo = (): void => {
    router.delete(route("admin.logo.destroy"));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
