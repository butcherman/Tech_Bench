<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Email Settings</div>
                    <EmailConfigForm :settings="settings" />
                    <Link
                        as="button"
                        :href="$route('admin.test-email')"
                        class="btn btn-warning w-100 my-2"
                        preserve-state
                    >
                        Send Test Email (uses saved values)
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";

defineProps<{
    settings: { [key: string]: string };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
