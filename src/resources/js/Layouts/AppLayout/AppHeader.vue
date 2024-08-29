<template>
    <nav class="navbar top-navbar fixed-top">
        <div class="navbar-logo-wrapper d-flex">
            <Link :href="$route('dashboard')" class="navbar-logo">
                <img :src="app.logo" class="mx-2" :alt="app.name" />
            </Link>
        </div>
        <div class="navbar-brand d-none d-md-flex">
            <h2>{{ app.name }}</h2>
        </div>
        <div class="navbar-data">
            <ul class="nav">
                <li class="nav-item">
                    <span
                        class="text-muted pointer"
                        title="Help "
                        @click="helpModal?.show"
                        v-tooltip
                    >
                        <fa-icon icon="fa-circle-question" />
                    </span>
                    <HelpModal ref="helpModal" />
                </li>
                <li class="nav-item">
                    <Link
                        :href="$route('about')"
                        class="text-muted"
                        :title="`About ${app.name}`"
                        v-tooltip
                    >
                        <fa-icon icon="fa-circle-info" />
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        :href="$route('notifications.index')"
                        as="button"
                        title="Notifications"
                        class="btn btn-primary btn-sm rounded-circle position-relative"
                        v-tooltip
                    >
                        <fa-icon icon="fa-bell" />
                        <span
                            class="badge bg-warning position-absolute top-0 start-100 translate-middle rounded-pill"
                        >
                            {{ app.userNotifications.new }}
                        </span>
                    </Link>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button
                            type="button"
                            class="btn btn-primary btn-sm rounded-pill dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            {{ app.user?.initials }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <Link
                                    :href="$route('user.user-settings.show')"
                                    class="dropdown-item"
                                >
                                    <fa-icon icon="fa-cog" />
                                    Settings
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="$route('user.change-password.show')"
                                    class="dropdown-item"
                                >
                                    <fa-icon icon="fa-key" />
                                    Change Password
                                </Link>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <Link
                                    as="button"
                                    :href="$route('logout')"
                                    class="dropdown-item"
                                    method="POST"
                                >
                                    <fa-icon icon="fa-sign-out-alt" />
                                    Logout
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <button
                        type="button"
                        class="navbar-toggler"
                        @click="$emit('navbar-toggle')"
                    >
                        <span class="navbar-toggler-icon" />
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup lang="ts">
import HelpModal from "@/Help/HelpModal.vue";
import { ref } from "vue";
import { useAppStore } from "@/Store/AppStore";

defineEmits(["navbar-toggle"]);
const app = useAppStore();
const helpModal = ref<InstanceType<typeof HelpModal> | null>(null);
</script>
