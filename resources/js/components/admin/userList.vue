<template>
    <div>
        <h4 class="text-center">Select User to Modify</h4>
        <vue-good-table
            :columns="columns"
            :rows="user_list"
            styleClass="vgt-table striped bordered"
        >
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'user'">
                    <a :href="route(action_route, data.row.user_id)">{{data.row.full_name}}</a>
                </span>
            </template>
        </vue-good-table>
    </div>
</template>
<script>
export default {
    props: [
        'user_list',
        'action_route',
    ],
    data() {
        return {
            columns: [
                {
                    label: 'User',
                    field: 'user',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'Email Address',
                    field: 'email',
                    filterOptions: {
                        enabled: true,
                    }
                },
                {
                    label: 'Last Login Date',
                    field: 'user_logins.0.created_at',
                }
            ],
            rows: [],
        }
    },
    created() {
        // this.getUserList();
        console.log(this.user_list);
    },
    methods: {
        // getUserList()
        // {
        //     axios.get(this.get_url)
        //         .then(res => {
        //             this.rows = res.data;
        //         })
        //         .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        // },
        // clickName(par)
        // {
        //     var url = this.action_url.replace(':id', par.row.user_id);
        //     location.href = url;
        // }
    }
}
</script>
