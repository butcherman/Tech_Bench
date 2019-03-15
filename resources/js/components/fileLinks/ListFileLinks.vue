<template>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="check-all-links" v-model="selectAll" @click="select" /></th>
                <th>Link Name</th>
                <th># of Files</th>
                <th>Expire Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    <click-confirm>
                        <button class="btn btn-info" id="delete-checked" @click="deleteChecked" :disabled="button.dis">{{button.text}}</button>
                    </click-confirm>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr v-for="link in links" >
                <td class="text-center"><input type="checkbox" class="check-link" :value="link.link_id" v-model="selected" /></td>
                <td :class=link.showClass>
                    <a :href=link.url>{{link.link_name}}</a>
                </td>
                <td :class=link.showClass>{{link.file_link_files_count}}</td>
                <td :class=link.showClass>{{link.expire}}</td>
                <td :class=link.showClass>
                    <a :href="routes.emLink.replace(':hash', link.link_hash)" title="Email Link" :data-id=link.link_id class="text-muted remove-link"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                    <click-confirm class="d-inline">
                        <span class="pointer" :data-id="link.link_id" @click="deleteLink" ><i class="fa fa-trash"></i></span>
                    </click-confirm>
                </td>
            </tr>
            <tr v-if="links.length === 0">
                <td colspan="5" class="text-center">No File Links</td>
            </tr>
        </tbody> 
    </table>  
</template>

<script>
    export default {
        props: [
            'get_links_route',
            'del_link_route',
            'em_link_route'
        ],
        data() {
            return {
                links:     [],
                link:      {},
                link_id:   '',
                selected:  [],
                selectAll: false,
                button: 
                {
                    text: 'Delete Checked',
                    dis:   false
                },
                routes:
                {
                    getLinks: this.get_links_route,
                    delLink: this.del_link_route,
                    emLink: this.em_link_route
                }
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            //  List the links the user owns
            fetchLinks() 
            {
                axios.get(this.routes.getLinks)
                    .then(res => {
                        this.links       = res.data;
                        this.button.text = 'Delete Checked';
                        this.button.dis  = false;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Activate the check all box
            select()
            {
                this.selected = [];
                if(!this.selectAll)
                {
                    for(let i in this.links)
                    {
                        this.selected.push(this.links[i].link_id);
                    }
                }
            },
            //  Delete a single link
            deleteLink: function(e)
            {
                var obj = this;
                
                var url = obj.routes.delLink.replace(':linkID', e.currentTarget.dataset.id)
                axios.delete(url)
                    .then(res => {
                        obj.fetchLinks();
                    });
            },
            //  Delete multiple links
            deleteChecked()
            {
                var obj = this;
                
                if(obj.selected.length != 0)
                {
                    obj.button.text = 'Loading...';
                    obj.button.dis  = true;
                    this.selected.forEach(function(item)
                    {
                        var url = obj.routes.delLink.replace(':linkID', item)
                        axios.delete(url)
                            .then(res => {
                                obj.fetchLinks();
                        })
                        .catch(error => alert('There was an issue processing your request\nPlease try again later.  \n\nError Info: '+error))
                    });
                }
            }
        }
    }
</script>
