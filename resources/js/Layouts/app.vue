<template>
    <div class="app-layout">
        <nav class="navbar top-navbar fixed-top">
            <div class="navbar-logo-wrapper d-flex">
                <a class="navbar-logo" :href="route('dashboard')"><img :src="app.logo" class="mr-2" :alt="app.name"/></a>
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
                <b-dropdown variant="link" title="Account" v-b-tooltip.hover>
                    <template #button-content>
                        <b-avatar variant="warning" :text="user.initials"></b-avatar>
                    </template>
                    <inertia-link as="b-dropdown-item" :href="route('settings.index')"><i class="fas fa-cog"></i> Settings</inertia-link>
                    <inertia-link as="b-dropdown-item" :href="route('password.edit', 'change')"><i class="fas fa-key"></i> Change Password</inertia-link>
                    <b-dropdown-divider></b-dropdown-divider>
                    <inertia-link as="b-dropdown-item" method="post" :href="route('logout')"><i class="fas fa-sign-out-alt"></i> Logout</inertia-link>
                </b-dropdown>
                <button class="navbar-toggler d-lg-none" type="button" @click="showNav = !showNav">
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
                    <b-alert :variant="$page.props.flash.type" :show="$page.props.flash.message ? 30 : false">
                        <p class="text-center">{{$page.props.flash.message}}</p>
                    </b-alert>
                    <slot />
                </div>
                <footer class=" footer page-footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2016-2021<span class="d-none d-md-inline"> Butcherman - All rights reserved.</span></span>
                        <span class="text-muted float-none float-sm-right d-block mt-1 mt-sm-0 text-center">{{app.version}}</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            //
        },
        data() {
            return {
                //
                showNav: false,
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
            app()
            {
                return this.$page.props.app;
            },
            user()
            {
                return this.$page.props.user;
            },
            navbarActive()
            {
                return this.showNav ? 'active' : '';
            },
            navbar()
            {
                return this.$page.props.navBar;
            }
        },
        watch: {
             //
        },
        methods: {
            //

        }
    }
</script>
