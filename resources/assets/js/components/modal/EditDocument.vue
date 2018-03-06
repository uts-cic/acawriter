<template>
    <modal name="edit-document" transition="pop-out" :width="modalWidth" :height="400" @before-open="beforeOpen">
        <div class="row">
            <div class="col-md-12">
                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
            </div>
            <div v-if="flash!=''" class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{flash}}</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-default">Update Document</div>
                    <div class="card-block p-5">
                            <label for="document-name">Document Name</label>
                            <input type="text" class="form-control" id="document-name" v-model="m_document.name" />
                            <input type="hidden" name="document_id" v-model="m_document.id" />
                            <br />
                            <button class="btn btn-dark" v-on:click="saveModalDocument()">Save</button> &nbsp;
                            <button class="btn btn-dark" v-on:click="close()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 400
    export default {
        name: "editDocument",
        data () {
            return {
                modalWidth: MODAL_WIDTH,
                m_document:{
                    id:0,
                    name: ''
                },
                errors:[],
                flash:''
            }
        },
        created () {
            this.modalWidth = window.innerWidth < MODAL_WIDTH
                ? MODAL_WIDTH / 2
                : MODAL_WIDTH
        },
        methods:{
            beforeOpen (event) {
                if(event) {
                    this.m_document.id = event.params.data.id;
                    this.m_document.name = event.params.data.name;
                }
              //console.log(event.params.data);
            },
            saveModalDocument() {
                if(this.m_document.name == '') {
                    this.errors.push({'message': 'Document name cannot be empty'});
                    return false;
                }
                let data = {'action': 'edit', 'doc': this.m_document};
                axios.post('documents/action', data).then((response) => {
                    this.$data.flash = response.data.message;
                    setTimeout(() => {
                        this.$data.flash = '';
                    }, 3000);
                    this.$modal.hide("edit-document");
                }, (err) => {
                    this.$data.errors.push(e)
                });
            },
            close() {
                this.$modal.hide("edit-document");
            }
        }
    }
</script>