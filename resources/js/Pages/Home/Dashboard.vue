<template>
    <h1 class="text-center">Logged In</h1>
    <!-- <div>
        <div class="row justify-content-center">
            <div class="col-12 grid-margin">
                <notifications />
            </div>
        </div>
        <div v-if="tools.length > 0" class="row justify-content-center">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Tools</div>
                        <div class="row justify-content-center">
                            <div
                                class="col-lg-3"
                                v-for="tool in tools"
                                :key="tool.name"
                            >
                                <inertia-link
                                    as="b-button"
                                    variant="info"
                                    :href="route(tool.route)"
                                    block
                                    pill
                                >
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
                <quick-links
                    title="Customer Bookmarks"
                    listType="cust"
                    :listData="bookmarks.customers"
                ></quick-links>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <quick-links
                    title="Recent Customers"
                    listType="cust"
                    :listData="recents.customers"
                ></quick-links>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <quick-links
                    title="Tech Tip Bookmarks"
                    listType="tips"
                    :listData="bookmarks.tips"
                ></quick-links>
            </div>
            <div class="col-xl-3 col-md-6 grid-margin stretch-card">
                <quick-links
                    title="Recent Tech Tips"
                    listType="tips"
                    :listData="recents.tips"
                ></quick-links>
            </div>
        </div>
    </div> -->
</template>

<script>
    // import App           from '../../Layouts/app';
    // import QuickLinks    from '../../Components/Home/quickLinks.vue';
    // import Notifications from '../../Components/Home/notifications.vue';


    // export default {
    //     layout: App,
    //     components: { Notifications, QuickLinks },
    //     props: {
    //         /**
    //          * Array of objects from two models -
    //          *      app/Models/UserCustomerBookmark
    //          *      app/Models/UserTechTipBoookmark
    //          */
    //         bookmarks: {
    //             type:     Object,
    //             required: true,
    //         },
    //         /**
    //          * Array of objects from two models
    //          *      app/Models/UserCustomerRecent
    //          *      app/Models/UserTechTipRecent
    //          */
    //         recents: {
    //             type:     Object,
    //             required: true,
    //         },
    //         /**
    //          * If any modules are installed, any tool icons will be listed here
    //          */
    //         tools: {
    //             type:     Array,
    //             required: true,
    //         },
    //     },
    //     mounted() {
    //          //  sort the bookmarks by name
    //         this.bookmarks.customers.sort((a, b) => a.name.localeCompare(b.name));
    //         this.bookmarks.tips.sort((a, b) => a.subject.localeCompare(b.subject));
    //     },
    //     metaInfo: {
    //         title: 'Dashboard',
    //     }
    // }
</script>
