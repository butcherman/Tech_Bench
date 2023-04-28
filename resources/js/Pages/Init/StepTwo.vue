<template>
    <InitLayout :step-id="2">
        <div class="card-title">Basic Settings</div>
        <ConfigForm :settings="settings" :tz_list="tz_list" />
    </InitLayout>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import { ref, reactive, onMounted } from 'vue';
    // import { Layout } from './Layout.vue';
    import InitLayout from './InitLayout.vue';
    import StepNavigation from '@/Components/Base/StepNavigation.vue';

    import ConfigForm from '@/Components/Admin/ConfigForm.vue';

    interface timezoneType {
        [key:string]: {
            [key:string]:string
        }
    }

    interface settingsType {
        url          : string;
        timezone     : string;
        filesize     : number;
        allowOath    : boolean;
        allowRegister: boolean;
        tenantId     : string;
        clientId     : string;
        clientSecret : string;
        redirectUri  : string;
    }

    const $route = route;
    const props = defineProps<{
        settings: settingsType;
        tz_list: timezoneType;
    }>();
</script>
