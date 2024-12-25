<template>
    <div class="text-center m-3">
        <h1 class="font-bold text-2xl">Verify Settings</h1>
        <p class="text-center">Wrapping Up.</p>
        <p class="text-center">
            Please review the information below and make any necessary changes.
            When done, click Submit to complete the Tech Bench Setup.
        </p>
        <p class="text-center text-danger">
            Please note, it may take some time to finish. Please do not interact
            with the browser until the setup process is completed.
        </p>
        <div>
            <h6 class="font-semibold">
                Basic Settings
                <Link :href="$route('init.step-1')" class="float-end">
                    <EditBadge />
                </Link>
            </h6>
            <TableStacked
                :rows="basicSettings"
                title-case
                class="justify-self-center"
            />
            <hr class="mb-3 mt-2" />
            <h6 class="font-semibold">
                Email Settings
                <Link :href="$route('init.step-2')" class="float-end">
                    <EditBadge />
                </Link>
            </h6 class="font-semibold">
            <TableStacked
                :rows="emailSettings"
                title-case
                class="justify-self-center"
            />
            <hr class="mb-3 mt-2" />
            <h6 class="font-semibold">
                User Settings
                <Link :href="$route('init.step-3')" class="float-end">
                    <EditBadge />
                </Link>
            </h6 class="font-semibold">
            <TableStacked
                :rows="userSettings"
                title-case
                limit-width
                class="justify-self-center"
            />
            <hr class="mb-3 mt-2" />
            <h6 class="font-semibold">
                Administrator Account
                <Link :href="$route('init.step-4')" class="float-end">
                    <EditBadge />
                </Link>
            </h6 class="font-semibold">
            <TableStacked
                :rows="admin"
                title-case
                class="justify-self-center"
            />
            <hr class="mb-3 mt-2" />
            <Link :href="$route('init.finish')" class="btn btn-blue">
                Submit Settings
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import InitLayout from "@/Layouts/Init/InitLayout.vue";

defineProps<{
    basicSettings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
    };
    emailSettings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
    userSettings: passwordPolicy;
    admin: user;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
