<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">{{title}}</div>
            <b-list-group>
                <b-list-group-item v-for="item in itemList" :key="item.id">
                    <inertia-link
                        v-if="item.title !== null"
                        size="sm"
                        as="b-button"
                        variant="info"
                        pill
                        block
                        :href="route(item.route, item.slug)"
                    >{{item.title}}</inertia-link>
                </b-list-group-item>
            </b-list-group>
            <h4 v-if="!listData.length" class="text-center">
                No {{title}}
            </h4>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                required: true,
            },
            listData: {
                type: Array,
                required: true,
            },
            listType: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                itemList: [],
            }
        },
        created() {
            //
        },
        mounted() {
            this.assignItemList();
        },
        computed: {
            //
        },
        watch: {
            //
        },
        methods: {
            assignItemList()
            {
                switch(this.listType)
                {
                    case 'cust':
                        this.assignCustList();
                        break;
                    case 'tips':
                        this.assignTipsList();
                        break;
                }
            },
            assignCustList()
            {
                this.listData.forEach(item => {
                    this.itemList.push({
                        id: item.cust_id,
                        title: item.name,
                        slug: item.slug,
                        route: 'customers.show'
                    });
                });
            },
            assignTipsList()
            {
                this.listData.forEach(item => {
                    this.itemList.push({
                        id: item.tip_id,
                        title: item.subject,
                        slug: item.slug,
                        route: 'tech-tips.show'
                    });
                });
            },
        },
    }
</script>
