<template>
    <div class="app-layout">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <Link
                    :href="route('dashboard')"
                    class="navbar-logo"
                >
                    <img :src="app.logo" class="mr-2" :alt="app.name"/>
                </Link>
            </div>
            <div class="navbar-brand d-none d-md-flex">
                <h2>{{ app.name }}</h2>
            </div>
            <div class="navbar-data">
                <ul class="nav">
                    <li class="nav-item">
                        <Link
                            :href="route('about')"
                            :title="`About ${app.name}`"
                            class="text-muted"
                            v-tooltip
                        >
                            <fa-icon icon="fa-circle-info" />
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            :href="route('dashboard')"
                            as="button"
                            title="Notifications"
                            class="btn btn-pill btn-primary position-relative"
                            v-tooltip
                        >
                            <fa-icon icon="fa-bell" />
                            <span
                                class="badge bg-warning position-absolute top-0 start-100 translate-middle rounded-pill"
                            >
                                {{ notif.new }}
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
                                {{ app.user.initials }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <Link
                                        :href="route('settings.index')"
                                        class="dropdown-item"
                                    >
                                        <fa-icon icon="fa-cog" />
                                        Settings
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('password.index')"
                                        class="dropdown-item"
                                    >
                                        <fa-icon icon="fa-key" />
                                        Change Password
                                    </Link>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <Link
                                        as="button"
                                        :href="route('logout')"
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
                            @click="navbarActive = !navbarActive"
                        >
                            <span class="navbar-toggler-icon" />
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <nav id="side-nav" class="sidebar" :class="{ 'active' : navbarActive }">
                <ul class="nav">
                    <li class="nav-item" v-for="link in navBar" :key="link.name">
                        <Link class="nav-link" :href="link.route">
                            <fa-icon :icon="link.icon" />
                            <span class="menu-title">{{ link.name }}</span>
                        </Link>
                    </li>
                </ul>
            </nav>
            <div id="content" class="content">
                <div class="content-wrapper">
                    <nav v-if="breadcrumbs.length">
                        <ol class="breadcrumb">
                            <li
                                v-for="crumb in breadcrumbs"
                                :key="crumb.title"
                                :class="{ 'active' : crumb.is_current_page }"
                                class="breadcrumb-item"
                            >
                                <Link
                                    v-if="!crumb.is_current_page"
                                    :href="crumb.url"
                                >
                                    {{ crumb.title }}
                                </Link>
                                <span v-else>
                                    {{ crumb.title }}
                                </span>
                            </li>
                        </ol>
                    </nav>
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger text-center">
                        <div v-for="error in errors">
                            {{ error }}
                        </div>
                    </div>
                    <div v-if="warning" class="alert alert-warning text-center">
                        {{ warning }}
                    </div>
                    <div v-if="success" class="alert alert-success text-center">
                        {{ success }}
                    </div>
                    <slot />
                </div>
                <footer class=" footer page-footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Copyright &copy;
                            {{ app.copyright }}
                            <span class="d-none d-md-inline"> Butcherman - All rights reserved.</span>
                        </span>
                        <span class="text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                            {{ app.version }}
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { computed, ref } from 'vue';
    import { usePage }       from '@inertiajs/inertia-vue3';
    import { Inertia }       from '@inertiajs/inertia';

    import type { pageInterface } from '@/Types';

    const app         = computed(() => usePage<pageInterface>().props.value.app);
    const navBar      = computed(() => usePage<pageInterface>().props.value.navbar);
    const notif       = computed(() => usePage<pageInterface>().props.value.notifications);
    const breadcrumbs = computed(() => usePage<pageInterface>().props.value.breadcrumbs);
    const errors      = computed(() => usePage<pageInterface>().props.value.errors);
    const warning     = computed(() => usePage<pageInterface>().props.value.flash.warning);
    const success     = computed(() => usePage<pageInterface>().props.value.flash.success);

    const navbarActive = ref(false);

    Inertia.on('navigate', () => navbarActive.value = false);
</script>

<style scoped lang="scss">
    @import "../../scss/Layouts/appLayout.scss";
    .dropdown-menu {
        margin: 0 !important;
    }
</style>
