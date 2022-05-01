<template>
    <div class="app-layout">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <inertia-link class="navbar-logo" :href="route('dashboard')"><img :src="app.logo" class="mr-2" :alt="app.name"/></inertia-link>
            </div>
            <div class="navbar-brand d-none d-md-flex">
                <h2>{{app.name}}</h2>
            </div>
            <div class="navbar-data">
                <!-- <inertia-link href="#" class="text-muted" title="Help" v-b-tooltip.hover>
                    <i class="far fa-question-circle"></i>
                </inertia-link> -->
                <inertia-link :href="route('about')" class="text-muted" :title="'About '+app.name" v-b-tooltip.hover>
                    <i class="fas fa-info-circle"></i>
                </inertia-link>
                <inertia-link as="b-button" :href="route('dashboard')" size="sm" pill variant="info">
                    <i class="fas fa-bell"></i>
                    <b-badge pill variant="warning">{{notifCount}}</b-badge>
                </inertia-link>
                <b-dropdown variant="link" title="Account" v-b-tooltip.hover>
                    <template #button-content>
                        <b-avatar variant="warning" :text="app.user.initials"></b-avatar>
                    </template>
                    <inertia-link as="b-dropdown-item" :href="route('settings.index')"><i class="fas fa-cog"></i> Settings</inertia-link>
                    <inertia-link as="b-dropdown-item" :href="route('password.index')"><i class="fas fa-key"></i> Change Password</inertia-link>
                    <b-dropdown-divider></b-dropdown-divider>
                    <inertia-link as="b-dropdown-item" method="post" :href="route('logout')"><i class="fas fa-sign-out-alt"></i> Logout</inertia-link>
                </b-dropdown>
                <button class="navbar-toggler d-xl-none" type="button" @click="showNav = !showNav">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-nav" :class="navbarActive" id="side-nav">
                <ul class="nav">
                    <li class="nav-item" v-for="l in navbar" :key="l.name">
                        <inertia-link class="nav-link" :href="l.route">
                            <i class="menu-icon" :class="l.icon"></i>
                            <span class="menu-title">{{l.name}}</span>
                        </inertia-link>
                    </li>
                </ul>
            </nav>
            <div class="content">
                <div class="content-wrapper">
                    <b-breadcrumb v-if="breadcrumbs.length" :items="breadcrumbs"></b-breadcrumb>
                    <b-alert :variant="$page.props.flash.type" :show="$page.props.flash.message ? 30 : false">
                        <p class="text-center">{{$page.props.flash.message}}</p>
                    </b-alert>
                    <b-alert :variant="alert.type" :show="alert.message ? 30 : false">
                        <p class="text-center">{{alert.message}}</p>
                    </b-alert>
                    <slot />
                </div>
                <axios-error></axios-error>
                <footer class=" footer page-footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2016-2022<span class="d-none d-md-inline"> Butcherman - All rights reserved.</span></span>
                        <span class="text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center">{{app.version}}</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import { Inertia } from '@inertiajs/inertia';

    export default {
        data() {
            return {
                showNav:    false,
                notifCount: this.$page.props.app.notifCount,
                alert: {
                    type:    null,
                    message: null,
                },
            }
        },
        created() {
            Inertia.on('navigate', () => {
                this.showNav = false;
            });
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
            //  All application information
            app()
            {
                return this.$page.props.app;
            },
            //  If the navbar is open or closed
            navbarActive()
            {
                return this.showNav ? 'active' : '';
            },
            //  Dynamically built navbar
            navbar()
            {
                return this.$page.props.navBar;
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
            }
        },
        metaInfo: {
            title: 'Welcome',
            titleTemplate: '%s | Tech Bench',
        }
    }
</script>
