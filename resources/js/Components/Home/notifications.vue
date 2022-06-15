<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Notifications</div>
            <b-table-simple small responsive-sm striped sticky-header class="p-4">
                <b-thead>
                    <b-tr class="row">
                        <b-th class="col-1">
                            <b-form-checkbox  @change="checkAll" />
                        </b-th>
                        <b-th class="col-7 text-left">Subject</b-th>
                        <b-th class="col-sm-4 d-none d-sm-flex">Date</b-th>
                    </b-tr>
                </b-thead>
                <b-tbody>
                    <b-tr v-if="!notificationList.length">
                        <b-td colspan="3" class="text-center">No Notifications</b-td>
                    </b-tr>
                    <tr v-else v-for="(notif, index) in notificationList" :key="index" class="row">
                        <b-td>
                            <b-form-checkbox v-model="notif.checked" />
                        </b-td>
                        <b-td class="col-7 text-left pointer" @click="openMessage(notif)">
                            <i v-if="notif.loading" class="fas fa-spinner fa-spin" />
                            <i v-else-if="notif.read" class="fas fa-envelope-open-text" />
                            <i v-else class="fas fa-envelope" />
                            {{notif.subject}}
                        </b-td>
                        <b-td class="col-sm-4 d-none d-sm-flex">
                            {{notif.date}}
                        </b-td>
                    </tr>
                </b-tbody>
            </b-table-simple>
            <div v-if="hasCheckedValue">
                 <b-button class="w-md-25 w-sm-50 m-1" variant="warning" @click="markChecked('read')">
                    <i class="fas fa-check" />
                    Mark Read
                </b-button>
                <b-button class="w-md-25 w-sm-50 m-1" variant="danger" @click="markChecked('delete')">
                    <i class="far fa-trash-alt" />
                    Delete
                </b-button>
            </div>
        </div>
        <notification-base
            ref="notification-base"
            :notification="openNotification"
            @delete="deleteMessage"
        ></notification-base>
    </div>
</template>

<script>
    import { useNotificationStore } from '../../Stores/notificationStore';
    import { mapStores } from 'pinia';

    export default {
        props: {
            notifications: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                notificationList: [],
                openNotification: {},
            }
        },
        created() {
            //
        },
        mounted() {
            this.parseNotifications();
            console.log(this.notificationStore.notificationList)
        },
        computed: {
            hasCheckedValue()
            {
                let checked = false;

                this.notificationList.forEach(item => {
                    if(item.checked)
                    {
                        checked = true;
                    }
                });

                return checked;
            },
            ...mapStores(useNotificationStore),
        },
        watch: {
            //
        },
        methods: {
            parseNotifications()
            {
                this.notificationList = [];
                this.notificationStore.notificationList.forEach(item => {
                    let newObj = {
                        id     : item.id,
                        subject: item.data.subject,
                        date   : item.created_at,
                        read   : item.read_at ? true : false,
                        checked: false,
                        loading: false,
                        data   : item.data ? item.data : null,
                        type   : item.data ? item.type : null,
                        html   : item.html ? item.html : null,
                        text   : item.text ? item.text : null,
                    }

                    this.notificationList.push(newObj);
                });
            },
            checkAll(value)
            {
                this.notificationList.forEach(item => item.checked = value);
            },
            openMessage(msg)
            {
                this.openNotification = msg;
                this.$refs['notification-base'].openModal();
                this.markMessage('read', [msg.id]);
            },
            markChecked(markAs)
            {
                let messageList = [];
                this.notificationList.forEach(item => {
                    if(item.checked)
                    {
                        item.loading = true;
                        messageList.push(item.id);
                    }
                });

                this.markMessage(markAs, messageList);
            },
            markMessage(markAs, messageList)
            {
                axios.post(route('notifications'), {
                    action: markAs,
                    list  : messageList,
                }).then(res => {
                    this.notificationStore.importNotifications(res.data.notifications);
                    this.parseNotifications();
                }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteMessage()
            {
                this.openNotification.loading = true;
                this.markMessage('delete', [this.openNotification.id]);
            }
        },
    }
</script>
