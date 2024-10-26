<template>
    <div>
        <Head title="Customer Settings" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Settings</div>
                        <CustomerSettingsForm
                            :select_id="select_id"
                            :update_slug="update_slug"
                            :default_state="default_state"
                            :auto_purge="auto_purge"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerSettingsForm from "@/Forms/Customer/CustomerSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
