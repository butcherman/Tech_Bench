<template>
    <Head title="Customer Settings" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Default Settings</div>
                        <CustomerSettingsForm
                            :current-settings="{
                                selectId,
                                updateSlug,
                                defaultState,
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";

defineProps<{
    selectId: boolean;
    updateSlug: boolean;
    defaultState: string;
}>();
</script>

<script lang="ts">
export default { layout: App };
</script>
