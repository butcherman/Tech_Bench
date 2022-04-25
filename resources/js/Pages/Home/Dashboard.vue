<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Notifications</div>
                        <b-table-simple small responsive-sm striped>
                            <b-thead>
                                <b-tr class="row">
                                    <b-th class="col-1">&nbsp;</b-th>
                                    <b-th class="text-left col-11 col-sm-7">Subject</b-th>
                                    <b-th class="col-sm-4 d-none d-sm-flex">Date</b-th>
                                </b-tr>
                            </b-thead>
                            <b-tbody>
                                <tr v-if="notificationList.length == 0">
                                    <td colspan="3" class="text-center">No Notifications</td>
                                </tr>
                                <notification-base
                                    v-else
                                    v-for="notification in notificationList"
                                    :key="notification.id"
                                    :notification="notification"
                                    :reset_watch="checkedList.length ? false : true"
                                    @read="markMessage"
                                    @delete="deleteMessage"
                                    @checked="checkedMessage"
                                ></notification-base>
                            </b-tbody>
                            <b-tfoot>
                                <b-tr v-if="checkedList.length" class="row">
                                    <b-td class="text-left col-4 col-sm-1">
                                        <b-button size="sm" block variant="info" @click="markMessage(checkedList)">Mark Read</b-button>
                                        <b-button size="sm" block variant="danger" @click="deleteMessage(checkedList)">Delete</b-button>
                                    </b-td>
                                    <b-td colspan="2"></b-td>
                                </b-tr>
                            </b-tfoot>
                        </b-table-simple>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="tools.length > 0" class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Tools</div>
                        <div class="row justify-content-center">
                            <div class="col-lg-3" v-for="tool in tools" :key="tool.name">
                                <inertia-link as="b-button" :href="route(tool.route)" block pill variant="info">
                                    <i :class="tool.icon"></i>
                                    {{tool.name}}
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Bookmarks</div>
                        <b-list-group>
                            <b-list-group-item v-for="cust in bookmarks.customers" :key="cust.cust_id">
                                <inertia-link v-if="cust.name !== null" as="b-button" :href="route('customers.show', cust.slug)" block size="sm" variant="info" pill>{{cust.name}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="bookmarks.customers.length == 0" class="text-center">No Bookmarks</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent Customers</div>
                        <b-list-group>
                            <b-list-group-item v-for="cust in recents.customers" :key="cust.cust_id">
                                <inertia-link v-if="cust.name !== null" as="b-button" :href="route('customers.show', cust.slug)" block size="sm" variant="info" pill>{{cust.name}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="recents.customers.length == 0" class="text-center">No Recent Customers</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Tech Tip Bookmarks</div>
                        <b-list-group>
                            <b-list-group-item v-for="tip in bookmarks.tips" :key="tip.tip_id">
                                <inertia-link v-if="tip.subject !== null" as="b-button" :href="route('tech-tips.show', tip.slug)" block size="sm" variant="info" pill>{{tip.subject}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="bookmarks.tips.length == 0" class="text-center">No Bookmarks</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Recent Tech Tips</div>
                        <b-list-group>
                            <b-list-group-item v-for="tip in recents.tips" :key="tip.tip_id">
                                <inertia-link v-if="tip.subject !== null" as="b-button" :href="route('tech-tips.show', tip.slug)" block size="sm" variant="info" pill>{{tip.subject}}</inertia-link>
                            </b-list-group-item>
                        </b-list-group>
                        <h4 v-if="recents.tips.length == 0" class="text-center">No Recent Tech Tips</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Database Notifications for user
             */
            notifications: {
                type:     Array,
                required: true,
            },
            /**
             * Array of objects from two models -
             *      app/Models/UserCustomerBookmark
             *      app/Models/UserTechTipBoookmark
             */
            bookmarks: {
                type:     Object,
                required: true,
            },
            /**
             * Array of objects from two models
             *      app/Models/UserCustomerRecent
             *      app/Models/UserTechTipRecent
             */
            recents: {
                type:     Object,
                required: true,
            },
            /**
             * If any modules are installed, any tool icons will be listed here
             */
            tools: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                notificationList: this.$page.props.app.user.notifications,
                checkedList: [],
            }
        },
        created() {
            //
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
            /**
             * Mark an unread message as read
             */
            markMessage(id)
            {
                if(Array.isArray(id))
                {
                    id.forEach((item) =>
                    {
                        axios.get(route('notifications.edit', item))
                            .then(res => {
                                this.notificationList = res.data.list;
                                this.eventHub.$emit('update-unread', res.data.unread);
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    });
                }
                else
                {
                    axios.get(route('notifications.edit', id))
                        .then(res => {
                            this.notificationList = res.data.list;
                            this.eventHub.$emit('update-unread', res.data.unread);
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }

                this.checkedList = [];
            },
            /**
             * Delete a notification
             */
            deleteMessage(id)
            {
                //  Delete multiple messages
                if(Array.isArray(id))
                {
                    id.forEach((item) =>
                    {
                        axios.delete(route('notifications.destroy', item))
                            .then(res => {
                                this.notificationList = res.data.list;
                                this.eventHub.$emit('update-unread', res.data.unread);
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    });
                }
                //  Delete only one message
                else
                {
                    axios.delete(route('notifications.destroy', id))
                        .then(res => {
                            this.notificationList = res.data.list;
                            this.eventHub.$emit('update-unread', res.data.unread);
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }

                this.checkedList = [];
            },
            /**
             * Add or remove message to array of checked messages
             */
            checkedMessage(checked, id)
            {
                if(checked)
                {
                    this.checkedList.push(id);
                }
                else
                {
                    this.checkedList.splice(this.checkedList.indexOf(id), 1);
                }
            }
        },
        metaInfo: {
            title: 'Dashboard',
        }
    }
</script>
