<template>
    <Head title="Email Settings" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Email Settings</div>
                        <EmailForm :settings="settings" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App            from '@/Layouts/app.vue';
    import EmailForm from '@/Components/Admin/EmailForm.vue';

    interface emSettingsType {
        host        : string;
        port        : number;
        encryption  : string;
        username    : string;
        password    : string;
        from_address: string;
        requireAuth : boolean;
    }

    defineProps<{
        settings:emSettingsType;
    }>();
</script>

<script lang="ts">
    export default { layout: App }
</script>
