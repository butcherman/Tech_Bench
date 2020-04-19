<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <customer-details
                    :cust_details="cust_details"
                    :is_fav="is_fav"
                    :can_del="can_del"
                    @parentUpdated="reload"
                >
                </customer-details>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <customer-equipment :cust_id="cust_details.cust_id"></customer-equipment>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">customer contacts</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            cust_details: {
                type: Object,
                required: true,
            },
            can_del: {
                type: Boolean,
                default: false,
                required: false,
            },
            is_fav: {
                type: Boolean,
                default: false,
                required: false,
            }
        },
        data() {
            return {
                //
            }
        },
        created() {
            //
        },
        methods: {
            //
            reload()
            {
                console.log('reloaded');
            },
        }
    }
</script>
