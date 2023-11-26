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
                    <Link
                        :href="$route('about')"
                        :title="`About ${app.name}`"
                        class="text-muted"
                        v-tooltip
                    >
                        <fa-icon icon="fa-circle-info" />
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        :href="$route('dashboard')"
                        as="button"
                        title="Notifications"
                        class="btn btn-pill btn-primary position-relative"
                        v-tooltip
                    >
                        <fa-icon icon="fa-bell" />
                        <span
                            class="badge bg-warning position-absolute top-0 start-100 translate-middle rounded-pill"
                        >
                            {{ newNotificationCount }}
                        </span>
                    </Link>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button
                            type="button"
                            class="btn btn-primary dropdown-toggle btn-pill"
                            data-bs-toggle="dropdown"
                        >
                            {{ app.user?.initials }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <Link
                                    :href="$route('user.settings.index')"
                                    class="dropdown-item"
                                >
                                    <fa-icon icon="fa-cog" />
                                    Settings
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="$route('user.password')"
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
                        @click="toggleNavbar"
                    >
                        <span class="navbar-toggler-icon" />
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
// import { notifications, clearFetchInterval } from "@/State/NotificationState";
import { newNotificationCount } from "@/State/NotificationState";
import { toggleNavbar } from "@/State/LayoutState";

const page: pageData = usePage();
const app = computed(() => page.props.app);
</script>

<style scoped lang="scss">
@import "../../../scss/Layouts/appLayout.scss";

.top-navbar {
    background-color: $header-background;
    height: $header-height;
    margin: 0;
    padding: 0;
    width: 100%;
    border-bottom: $soft-border;
    .navbar-logo-wrapper {
        height: 100%;
        @media (min-width: $brk-md) {
            width: $navbar-width;
        }
        .navbar-logo {
            margin: auto;
            img {
                max-height: $header-height - 20;
            }
        }
    }
    .navbar-data {
        font-size: 1.2rem;
        margin-right: 10px;
        padding-right: 5px;
        .nav {
            .nav-item {
                margin-right: 5px;
                margin-left: 4px;
            }
        }
    }
}

.navbar-toggler {
    display: flex;
    @media (min-width: $brk-lg) {
        display: none;
    }
}

.dropdown-menu {
    margin: 0 !important;
}
</style>
