<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Verify Settings</div>
                        <p class="text-center">Wrapping Up.</p>
                        <p class="text-center">
                            Please review the information below and make any
                            necessary changes. When done, click Submit to
                            complete the Tech Bench Setup.
                        </p>
                        <p class="text-center text-danger">
                            Please note, it may take some time to finish. Please
                            do not interact with the browser until the setup
                            process is completed.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h6>
                            Basic Settings
                            <Link
                                :href="$route('init.step-1')"
                                class="float-end"
                            >
                                <EditBadge />
                            </Link>
                        </h6>
                        <TableStacked :rows="basicSettings" title-case />
                        <hr />
                        <h6>
                            Email Settings
                            <Link
                                :href="$route('init.step-2')"
                                class="float-end"
                            >
                                <EditBadge />
                            </Link>
                        </h6>
                        <TableStacked :rows="emailSettings" title-case />
                        <hr />
                        <h6>
                            User Settings
                            <Link
                                :href="$route('init.step-3')"
                                class="float-end"
                            >
                                <EditBadge />
                            </Link>
                        </h6>
                        <TableStacked
                            :rows="userSettings"
                            title-case
                            limit-width
                        />
                        <hr />
                        <h6>
                            Administrator Account
                            <Link
                                :href="$route('init.step-4')"
                                class="float-end"
                            >
                                <EditBadge />
                            </Link>
                        </h6>
                        <TableStacked :rows="admin" title-case />
                        <hr />
                        <Link
                            :href="$route('init.finish')"
                            class="btn btn-success"
                        >
                            Submit Settings
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import InitLayout from "@/Layouts/InitLayout.vue";

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
