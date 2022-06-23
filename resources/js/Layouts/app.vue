<template>
    <div class="app-layout">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <inertia-link
                    :href="route('dashboard')"
                    class="navbar-logo"
                >
                    <img :src="app.logo" class="mr-2" :alt="app.name"/>
                </inertia-link>
            </div>
            <div class="navbar-brand d-none d-md-flex">
                <h2>{{app.name}}</h2>
            </div>
            <div class="navbar-data">
                <!-- <inertia-link href="#" class="text-muted" title="Help" v-b-tooltip.hover>
                    <i class="far fa-question-circle"></i>
                </inertia-link> -->
                <inertia-link
                    :href="route('about')"
                    :title="`About ${app.name}`"
                    class="text-muted"
                    v-b-tooltip.hover
                >
                    <i class="fas fa-info-circle" />
                </inertia-link>
<!-- ///////////////////////// -->
                <inertia-link
                    :href="route('dashboard')"
                    size="sm"
                    as="b-button"
                    variant="info"
                    pill
                >
                    <i class="fas fa-bell" />
                    <b-badge pill variant="warning">
                        {{notificationStore.newCount}}
                    </b-badge>
                </inertia-link>
<!-- ///////////////////////// -->
                <b-dropdown variant="link" title="Account" v-b-tooltip.hover>
                    <template #button-content>
                        <b-avatar variant="warning" :text="app.user.initials" />
                    </template>
                    <inertia-link
                        :href="route('settings.index')"
                        as="b-dropdown-item"
                    >
                        <i class="fas fa-cog" />
                        Settings
                    </inertia-link>
                    <inertia-link
                        :href="route('password.index')"
                        as="b-dropdown-item"
                    >
                        <i class="fas fa-key" />
                        Change Password
                    </inertia-link>
                    <b-dropdown-divider />
                    <inertia-link
                        :href="route('logout')"
                        method="post"
                        as="b-dropdown-item"
                    >
                        <i class="fas fa-sign-out-alt" />
                        Logout
                    </inertia-link>
                </b-dropdown>
                <button
                    class="navbar-toggler d-xl-none"
                    type="button"
                    @click="showNav = !showNav"
                >
                    <i class="fas fa-bars" />
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
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
        </div>
    </div>
</template>

<script>
    import { Inertia }              from '@inertiajs/inertia';

    import { useNotificationStore } from '../Stores/notificationStore';
    import { mapStores } from 'pinia';

    export default {
        props: {
            app: {
                type: Object,
                required: true,
            },
            navbar: {
                type: Array,
                required: true,
            },
            notifications: {
                type: Object,
                required: true,
            },
            flash: {
                // type:
                required: false,
            }
        },
        data() {
            return {
                showNav:    false,
                alert: {
                    type:    null,
                    message: null,
                },
                flashMessage: [],
            }
        },
        created() {
            Inertia.on('navigate', () => {
                this.showNav = false;
            });
            //  Push all of the notifications into the NotificationStore
            this.notificationStore.importNotifications(this.notifications);
        },
        mounted() {
            //  Manually trigger alert from Vue Component
            this.eventHub.$on('show-alert', alert => {
                this.alert.message = alert.message;
                this.alert.type    = alert.type;
            });
            //  Manually cancel alert that was triggered
            this.eventHub.$on('clear-alert', () => {
                this.alert.message = null;
                this.alert.type    = null;
            });
            //  Update the notification bell with unread message count
            this.eventHub.$on('update-unread', unread => {
                this.notifCount = unread;
            });
        },
        computed: {
            //  If the navbar is open or closed
            navbarActive()
            {
                return this.showNav ? 'active' : '';
            },
            //  Dynamically built Breadcrumbs
            breadcrumbs()
            {
                var crumbs = [];
                this.$page.props.breadcrumbs.forEach(function(item)
                {
                    crumbs.push({
                        text: item.title,
                        href: item.url,
                        active: item.is_current_page,
                    });
                });

                return crumbs;
            },
            // Notification Store
            ...mapStores(useNotificationStore),
        },
        watch: {
            flash()
            {
                if(this.$page.props.flash.message !== null)
                {
                    this.flashMessage.push({
                        type   : this.$page.props.flash.type,
                        message: this.$page.props.flash.message,
                    });
                }
            }
        },
        metaInfo: {
            title: 'Welcome',
            titleTemplate: '%s | Tech Bench',
        }
    }
</script>
