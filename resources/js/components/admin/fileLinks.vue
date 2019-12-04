<template>
    <div class="table-responsive">
        <vue-good-table
            :columns="columns"
            :rows="this.user_links"
            styleClass="vgt-table striped bordered"
            @on-row-click="clickName"
        ></vue-good-table>
    </div>
</template>

<script>
export default {
    props: [
        'user_links'
    ],
    data() {
        return {
            rows: [],
            columns: [
            {
                label: 'User',
                field: 'full_name',
                filterOptions:
                {
                    enabled: true,
                }
            },
            {
                label: 'Total Links',
                field: 'file_links_count',
            },
            {
                label: 'Expired Links',
                field: 'expired_file_links_count',
            }],
        }
    },
    created()
    {
        // this.getLinks();
    },
    methods: {
        clickName(par)
        {
            var url = this.route('admin.user.showLinks', par.row.user_id);
            console.log(par.row);
            location.href = url;
        }
    }
}
</script>
