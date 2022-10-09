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
                                {{ app.notifCount }}
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
                                AC
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
                            data-bs-toggle="collapse"
                            data-bs-target="#navbar-content"
                        >
                            <span class="navbar-toggler-icon" />
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- <div class="container-fluid page-body-wrapper">
            <nav id="side-nav" class="sidebar sidebar-nav" :class="navbarActive">
                <ul class="nav">
                    <li class="nav-item" v-for="link in navbar" :key="link.name">
                        <inertia-link class="nav-link" :href="link.route">
                            <i class="menu-icon" :class="link.icon" />
                            <span class="menu-title">{{link.name}}</span>
                        </inertia-link>
                    </li>
                </ul>
            </nav>
            <div id="content" class="content">
                <div class="content-wrapper">
                    <nav v-if="breadcrumbs.length">
                        <ol class="breadcrumb">
                            <li
                                v-for="crumb in breadcrumbs"
                                :key="crumb.text"
                                :class="crumb.active ? 'active' : ''"
                                class="breadcrumb-item"
                            >
                                <inertia-link
                                    v-if="!crumb.active"
                                    :href="crumb.href"
                                >
                                    {{crumb.text}}
                                </inertia-link>
                                <span v-else>
                                    {{crumb.text}}
                                </span>
                            </li>
                        </ol>
                    </nav>
                    <div v-for="(message, index) in flashMessage" :key="index">
                        <b-alert :variant="message.type" :show="30">
                            <p class="text-center">{{message.message}}</p>
                        </b-alert>
                    </div>
                    <b-alert :variant="alert.type" :show="alert.message ? 30 : false">
                        <p class="text-center">{{alert.message}}</p>
                    </b-alert>
                    <slot />
                </div>
                <axios-error></axios-error>
                <validation-error></validation-error>
                <footer class=" footer page-footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Copyright &copy;
                            {{$page.props.app.copyright}}
                            <span class="d-none d-md-inline"> Butcherman - All rights reserved.</span>
                        </span>
                        <span class="text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                            {{app.version}}
                        </span>
                    </div>
                </footer>
            </div>
        </div> -->
    </div>
</template>

<script setup lang="ts">
    // import { Inertia }              from '@inertiajs/inertia';

    // import { useNotificationStore } from '../Stores/notificationStore';
    // import { mapStores } from 'pinia';

    import { computed } from 'vue';
    import { usePage } from '@inertiajs/inertia-vue3';

    const app     = computed(() => usePage().props.value.app);
    const errors  = computed(() => usePage().props.value.errors);
    const warning = computed(() => usePage().props.value.flash.warning);
    const success = computed(() => usePage().props.value.flash.success);



    // export default {
    //     props: {
    //         app: {
    //             type: Object,
    //             required: true,
    //         },
    //         navbar: {
    //             type: Array,
    //             required: true,
    //         },
    //         notifications: {
    //             type: Object,
    //             required: true,
    //         },
    //         flash: {
    //             // type:
    //             required: false,
    //         }
    //     },
    //     data() {
    //         return {
    //             showNav:    false,
    //             alert: {
    //                 type:    null,
    //                 message: null,
    //             },
    //             flashMessage: [],
    //         }
    //     },
    //     created() {
    //         Inertia.on('navigate', () => {
    //             this.showNav = false;
    //         });
    //         //  Push all of the notifications into the NotificationStore
    //         this.notificationStore.importNotifications(this.notifications);
    //     },
    //     mounted() {
    //         //  Manually trigger alert from Vue Component
    //         this.eventHub.$on('show-alert', alert => {
    //             this.alert.message = alert.message;
    //             this.alert.type    = alert.type;
    //         });
    //         //  Manually cancel alert that was triggered
    //         this.eventHub.$on('clear-alert', () => {
    //             this.alert.message = null;
    //             this.alert.type    = null;
    //         });
    //         //  Update the notification bell with unread message count
    //         this.eventHub.$on('update-unread', unread => {
    //             this.notifCount = unread;
    //         });
    //     },
    //     computed: {
    //         //  If the navbar is open or closed
    //         navbarActive()
    //         {
    //             return this.showNav ? 'active' : '';
    //         },
    //         //  Dynamically built Breadcrumbs
    //         breadcrumbs()
    //         {
    //             var crumbs = [];
    //             this.$page.props.breadcrumbs.forEach(function(item)
    //             {
    //                 crumbs.push({
    //                     text: item.title,
    //                     href: item.url,
    //                     active: item.is_current_page,
    //                 });
    //             });

    //             return crumbs;
    //         },
    //         // Notification Store
    //         ...mapStores(useNotificationStore),
    //     },
    //     watch: {
    //         flash()
    //         {
    //             if(this.$page.props.flash.message !== null)
    //             {
    //                 this.flashMessage.push({
    //                     type   : this.$page.props.flash.type,
    //                     message: this.$page.props.flash.message,
    //                 });
    //             }
    //         }
    //     },
    //     metaInfo: {
    //         title: 'Welcome',
    //         titleTemplate: '%s | Tech Bench',
    //     }
    // }
</script>

<style scoped lang="scss">
    @import "../../scss/Layouts/appLayout.scss";
    .dropdown-menu {
        margin: 0 !important;
    }
</style>
