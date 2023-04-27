<template>
    <div class="app-layout">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <Link
                    :href="$route('dashboard')"
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
                                {{ app.user?.initials }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <Link
                                        :href="$route('settings.index')"
                                        class="dropdown-item"
                                    >
                                        <fa-icon icon="fa-cog" />
                                        Settings
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="$route('settings.password.index')"
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
                    <div class="breadcrumbs-wrapper alert alert-info">
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
                                        class="text-muted"
                                        :href="crumb.url"
                                    >
                                        {{ crumb.title }}
                                    </Link>
                                    <span v-else class="text-muted">
                                        {{ crumb.title }}
                                    </span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div
                        v-if="Object.keys(errors).length > 0"
                        class="alert alert-danger text-center"
                    >
                        <div v-for="(err, name) in errors">
                            <span v-if="name == 'link'">
                                <Link :href="err">More Information</Link>
                            </span>
                            <span v-else>
                                {{ err }}
                            </span>
                        </div>
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
                        <span class="text-muted float-none float-sm-end d-block mt-1 mt-sm-0 text-center">
                            {{ app.version }}
                        </span>
                    </div>
                </footer>
            </div>
        </div>
        <div class="toast-container position-absolute top-0 start-50 translate-middle p-3">
            <template v-for="msg in flashMessage">
                <AlertToast :background="msg.type" :message="msg.message" />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
    import AlertToast  from '@/Components/Base/AlertToast.vue';
    import { usePage } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import { computed,
             ref,
             watch,
             onMounted } from 'vue';

    import { appProps,
             errorType,
             flashProps,
             navBarProps,
             pageInterface,
             breadcrumbsType,
             flashMessageType,
             notifciationProps, } from '@/Types';

    const $route       = route;
    const app          = computed<appProps>         (() => usePage<pageInterface>().props.app);
    const navBar       = computed<navBarProps[]>    (() => usePage<pageInterface>().props.navbar);
    const notif        = computed<notifciationProps>(() => usePage<pageInterface>().props.notifications);
    const breadcrumbs  = computed<breadcrumbsType[]>(() => usePage<pageInterface>().props.breadcrumbs);
    const errors       = computed<errorType>        (() => usePage<pageInterface>().props.errors);
    const flash        = computed<flashProps>       (() => usePage<pageInterface>().props.flash);

    const navbarActive = ref<boolean>(false);
    const flashMessage = ref<flashMessageType[]>([]);

    router.on('navigate', () => navbarActive.value = false);

    watch(flash, () => checkFlashMessages());
    onMounted(   () => checkFlashMessages());

    const checkFlashMessages = () => {
        for(const [type, message] of Object.entries(flash.value))
        {
            if(message !== null)
            {
                flashMessage.value.push({
                    type,
                    message,
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../scss/Layouts/appLayout.scss";

    .breadcrumbs-wrapper {
        margin : 0;
        margin-bottom: 15px;
        padding: 5px;
        nav {
            margin-bottom: 0 !important;
            .breadcrumb {
                margin : 0 !important;
                padding: 3px;
                a {
                    text-decoration: none;
                }
            }
        }
    }
    .dropdown-menu {
        margin: 0 !important;
    }

    .toast-container {
        margin-top: 3rem;
    }
</style>
