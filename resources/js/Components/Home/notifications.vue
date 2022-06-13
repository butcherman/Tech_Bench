<template>
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
                <b-tr v-if="!notificationList.length">
                    <td colspan="3" class="text-center">No Notifications</td>
                </b-tr>
                <b-tr
                    v-else
                    v-for="notification in notificationList"
                    :key="notification.id"
                >
                    <b-td class="col-1 text-center" @click="toggleChecked(notification)">
                        <i v-if="!notification.checked" class="far fa-check-square"></i>
                        <i v-else class="far fa-square"></i>
                    </b-td>
                    <b-td class="pointer col-7">message</b-td>
                    <b-td class="col-4">Date</b-td>
                </b-tr>
            </b-tbody>
            <b-tfoot>
                <b-tr>
                    <b-td colspan="2">Footer...</b-td>
                </b-tr>
            </b-tfoot>
        </b-table-simple>
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
                notificationList: [], // this.$page.props.app.user.notifications,
            }
        },
        created() {
            //
        },
        mounted() {
            //  Add the necessary properties for dealing with notifications
            // this.$page.props.app.user.notifications.forEach(item => {
            //     item.checked = false;

            //     this.notificationList.push(item);
            // });
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            toggleChecked(notif)
            {
                console.log(notif);
                notif.checked = !notif.checked;
            }
        },
    }
</script>
