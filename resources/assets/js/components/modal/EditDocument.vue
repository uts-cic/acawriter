<template>
    <modal name="edit-document" transition="pop-out" :width="modalWidth" :height="300" @before-open="beforeOpen">

        <div class="shadow p-3"><h5>Update document</h5></div>
        <div v-if="errors && errors.length" role="alert" class="alert alert-danger px-3">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <span v-for="error in errors">{{error.message}}</span>
        </div>
        <div class="px-3 mt-4">
            <div class="form-group mb-4">
                <label for="document-name">Document name</label>
                <input type="text" class="form-control" id="document-name" v-model="m_document.name" />
                <input type="hidden" name="document_id" v-model="m_document.id" />
            </div>
            <button class="btn btn-primary" v-on:click="saveModalDocument()">Save</button>
            <button class="btn btn-secondary ml-2" v-on:click="close()">Cancel</button>
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 400
    export default {
        name: "editDocument",
        data() {
            return {
                modalWidth: MODAL_WIDTH,
                m_document: {
                    id: 0,
                    name: ''
                },
                errors: []
            }
        },
        created() {
            this.modalWidth = window.innerWidth < MODAL_WIDTH
                ? MODAL_WIDTH / 2
                : MODAL_WIDTH
        },
        methods: {
            beforeOpen(event) {
                if(event) {
                    this.m_document.id = event.params.document.id;
                    this.m_document.name = event.params.document.name;
                    this.parent = event.params.parent;
                }
            },
            saveModalDocument() {
                if (this.m_document.name === '') {
                    this.errors.push({'message': 'Document name cannot be empty'});
                    return false;
                }

                let data = {'action': 'edit', 'doc': this.m_document};
                axios.post('/documents/action', data).then((response) => {
                    this.parent.onDocumentUpdate(response.data.message);
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
