<template>
    <div>
        <Head title="Log Settings" />
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg=4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Settings</div>
                        <LogSettingsForm :levels="levels" :channels="channels" :days="days" :values="values" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import LogSettingsForm from '@/Forms/Admin/Config/LogSettingsForm.vue';
    import { ref, reactive, onMounted } from 'vue';

    const props = defineProps<{
        levels: string[];
        channels: logChannels[];
        days: number;
        values: { [key: string]: string };
    }>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
