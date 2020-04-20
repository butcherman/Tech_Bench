<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <customer-details
                    :cust_details="cust_details"
                    :is_fav="is_fav"
                    :can_del="can_del"
                    @parentUpdated="reloadParent"
                >
                </customer-details>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <customer-equipment :cust_id="custDetails.cust_id"></customer-equipment>
            </div>
            <div class="col-md-7 grid-margin stretch-card">
                <customer-contacts :cust_id="custDetails.cust_id" :linked="linked"></customer-contacts>
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
                recomputeCounter: 0,
                custDetails: this.cust_details,
            }
        },
        mounted() {
            //
            // console.log(this.cust_details);
        },
        computed: {
            linked()
            {
                this.recomputeCounter;
                if(this.custDetails.parent_id != null || this.custDetails.child_count > 0)
                {
                    return true;
                }

                return false;
            }
        },
        watch: {
            //
        },
        methods: {
            reloadParent(data)
            {
                this.custDetails.parent_id = data;
                this.recomputeCounter++;
            },
        }
    }
</script>
