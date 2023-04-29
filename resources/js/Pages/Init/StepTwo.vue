<template>
    <InitLayout :step-id="2">
        <div class="card-title">Basic Settings</div>
        <ConfigForm
            :settings="settings"
            :tz_list="tz_list"
            @step-completed="nextStep"
        />
    </InitLayout>
</template>

<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import InitLayout from './InitLayout.vue';
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

    const nextStep = () => {
        router.get(route('init.step-3'));
    }
</script>
