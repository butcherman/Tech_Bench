<template>
    <v-menu rounded>
        <template #activator="{ props }">
            <v-btn icon v-bind="props" v-tooltip="'Account Information'">
                <v-avatar color="blue-lighten-3">
                    <span class="text-h5">{{ app.user?.initials }}</span>
                </v-avatar>
            </v-btn>
        </template>
        <v-card>
            <v-card-text>
                <div class="mx-auto">
                    <div class="text-center mb-2">
                        <h3 class="font-bold">{{ app.user?.full_name }}</h3>
                        <hr />
                    </div>
                    <v-list-item>
                        <Link :href="$route('user.user-settings.show')">
                            <font-awesome-icon icon="cog" />
                            Settings
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <Link :href="$route('user.change-password.show')">
                            <font-awesome-icon icon="key" />
                            Change Password
                        </Link>
                    </v-list-item>
                    <v-list-item class="border-t">
                        <Link :href="$route('logout')" method="POST">
                            <font-awesome-icon icon="sign-out-alt" />
                            Logout
                        </Link>
                    </v-list-item>
                </div>
            </v-card-text>
        </v-card>
    </v-menu>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Stores/AppStore";

const app = useAppStore();
</script>
