<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";

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

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4">
        <Card class="md:col-span-2" title="Verify Settings">
            <p class="text-center">Wrapping Up.</p>
            <p class="text-center">
                Please review the information below and make any necessary
                changes. When done, click Submit to complete the Tech Bench
                Setup.
            </p>
            <p class="text-center text-danger">
                Please note, it may take some time to finish. Please do not
                interact with the browser until the setup process is completed.
            </p>
        </Card>
        <Card>
            <h6 class="border-b p-1">
                Basic Settings
                <Link :href="$route('init.step-1')" class="float-end">
                    <EditBadge />
                </Link>
            </h6>
            <TableStacked :items="basicSettings" class="w-full" />
        </Card>
        <Card>
            <h6 class="border-b p-1">
                Email Settings
                <Link :href="$route('init.step-2')" class="float-end">
                    <EditBadge />
                </Link>
            </h6>
            <TableStacked :items="emailSettings" class="w-full" />
        </Card>
        <Card>
            <h6 class="border-b p-1">
                User Settings
                <Link :href="$route('init.step-3')" class="float-end">
                    <EditBadge />
                </Link>
            </h6>
            <TableStacked :items="userSettings" class="justify-self-center" />
        </Card>
        <Card>
            <h6 class="border-b p-1">
                Administrator Account
                <Link :href="$route('init.step-4')" class="float-end">
                    <EditBadge />
                </Link>
            </h6>
            <TableStacked :items="admin" class="w-full" />
        </Card>
        <Card class="md:col-span-2 text-center">
            <BaseButton :href="$route('init.finish')" class="w-3/4">
                Submit Settings
            </BaseButton>
        </Card>
    </div>
</template>
