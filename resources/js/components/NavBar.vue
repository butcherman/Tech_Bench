<template>
    <b-navbar toggleable="lg" id="main-nav">
        <b-navbar-brand :href="dashboard_route" class="navbar-brand">{{brand}}</b-navbar-brand>
        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav id="side-nav">
                <b-nav-item :href="dashboard_route">
                    <i class="fa fa-fw fa-home"></i> 
                    <span class="nav-link-text">Dashboard</span>
                </b-nav-item>
                <b-nav-item v-if="admin_route" :href="admin_route">
                    <i class="fa fa-fw fa-users"></i>
                    <span class="nav-link-text">Administration</span>
                </b-nav-item>
                <b-nav-item :href="links_route">
                    <i class="fa fa-fw fa-link"></i>
                    <span class="nav-link-text">File Links</span>
                </b-nav-item>
                <b-nav-item-dropdown v-for="(cat, index) in sysList" :key="index" :text="index">
                    <b-dropdown-item v-for="sys in cat" :key="sys.name" :href="sys.link">
                        <i class="fa fa-fw fa-bars"></i>
                        {{sys.name}}
                    </b-dropdown-item>
                </b-nav-item-dropdown>
                <b-nav-item :href="cust_route">
                    <i class="fa fa-fw fa-user"></i>
                    <span class="nav-link-text">Customers</span>
                </b-nav-item>
                <b-nav-item :href="account_route">
                    <i class="fa fa-fw fa-user"></i>
                    <span class="nav-link-text">Account Settings</span>
                </b-nav-item>
            </b-navbar-nav>
            <b-navbar-nav id="top-nav" class="ml-auto">
                <b-nav-item :href="logout_route">
                    <i class="fa fa-fw fa-sign-out"></i>
                    <span class="nav-link-text">Logout</span>
                </b-nav-item>
                <b-nav-item :href="help_route">
                    <i class="fa fa-fw fa-question-circle-o"></i> 
                    <span class="nav-link-text">About</span>
                </b-nav-item>
            </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
export default {
    props: [
        'brand', 
        'dashboard_route', 
        'logout_route', 
        'help_route',
        'links_route',
        'account_route',
        'admin_route',
        'sys_list',
        'cust_route',
    ],
    data() {
        return {
            sysList: JSON.parse(this.sys_list),
        }
    }
}
</script>
