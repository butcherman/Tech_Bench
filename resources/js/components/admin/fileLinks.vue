<template>
    <div class="table-responsive">
        <vue-good-table
            :columns="columns"
            :rows="rows"
            styleClass="vgt-table striped bordered"
            
            
        ></vue-good-table>
    </div>
</template>

<script>
export default {
    props: [
        'links_route',
    ],
    data() {
        return {
            rows: [],
            columns: [
            {
                label: 'User',
                field: 'name',
                filterOptions: 
                {
                    enabled: true,
                }
            },
            {
                label: 'Total Links',
                field: 'total',
            },
            {
                label: 'Expired Links',
                field: 'expired',
            }],
        }
    },
    created()
    {
        this.getLinks();
    },
    methods: {
        getLinks()
        {
            axios.get(this.links_route)
                .then(res => {
                    this.rows = res.data;
                    console.log(this.rows);
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        }
    }
}
</script>