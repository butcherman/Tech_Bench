<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Dashboard</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Notifications</div>
                        <b-table-simple small responsive striped>
                            <b-thead>
                                <b-tr>
                                    <b-th></b-th>
                                    <b-th class="text-left">Subject</b-th>
                                    <b-th>Date</b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <notification-base
                                    v-for="notification in notificationList"
                                    :key="notification.id"
                                    :notification="notification"
                                    @read="markMessage"
                                    @delete="deleteMessage"
                                ></notification-base>
                            </b-tbody>
                        </b-table-simple>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Bookmarks</div>
                        <b-list-group>
                            <b-list-group-item v-for="cust in bookmarks.customers" :key="cust.cust_id">
                                <inertia-link as="b-button" :href="route('customers.show', cust.slug)" block size="sm" variant="info" pill>{{cust.name}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="bookmarks.customers.length == 0" class="text-center">No Bookmarks</h4>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent Customers</div>
                        TODO - Make Me
                    </div>
                </div>
            </div> -->
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Tech Tip Bookmarks</div>
                        <b-list-group>
                            <b-list-group-item v-for="tip in bookmarks.tips" :key="tip.tip_id">
                                <inertia-link as="b-button" :href="route('tech-tips.show', tip.slug)" block size="sm" variant="info" pill>{{tip.subject}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="bookmarks.tips.length == 0" class="text-center">No Bookmarks</h4>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent Tech Tips</div>
                        TODO - MAKE ME
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            notifications: {
                type:     Array,
                required: true,
            },
            bookmarks: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                notificationList: this.notifications,
            }
        },
        created() {
            //
            // console.log(this.notifications[0].type.split(/\W+/).pop())
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            markMessage(id)
            {
                axios.get(route('notifications.edit', id))
                    .then(res => {
                        this.notificationList = res.data;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteMessage(id)
            {
                axios.delete(route('notifications.destroy', id))
                    .then(res => {
                        console.log(res);
                        this.notificationList = res.data;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
        metaInfo: {
            title: 'Dashboard',
        }
    }
</script>
