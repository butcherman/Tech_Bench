<template>
    <div>
        <vue-good-table 
            :columns="columns" 
            :rows="rows" 
            styleClass="vgt-table striped bordered" 
            @on-row-click="clickName"></vue-good-table>
    </div>
</template>
<script>
export default {
    props: [
        'get_url',
        'action_url',
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
                    field: 'last'
                }
            ], 
            rows: [],
        }
    },
    created() {
        this.getUserList();
    },
    methods: {
        getUserList()
        {
            axios.get(this.get_url)
                .then(res => {
                    this.rows = res.data;
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        clickName(par)
        {
            var url = this.action_url.replace(':id', par.row.user_id);
            location.href = url;
        }
    }
}
</script>
