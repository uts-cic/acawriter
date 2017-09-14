<template>
    <div id="editor">
        <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala>
        <br />
        <div v-model="preview">{{preview}}</div>
        <br />
        <hr />
        <h4>GraphQl data</h4>
        <div v-if="loading" class="loading">Loading .....</div>
        <article v-for="employee in viewer.employeeList">
             {{employee.firstName}} {{employee.lastName}}  ({{employee.birthDate}})
        </article>
        <h3>Products</h3>
        <ul>
            <li v-for="product in viewer.productList" class="list=item-group">
                {{product.name}} : ${{product.unitPrice}}
            </li>
        </ul>


    </div>
</template>

<script>
    import VueFroala from 'vue-froala-wysiwyg';
    Vue.use(VueFroala);

    import gql from 'graphql-tag';
    const POSTS_QUERY = gql`
    {
        viewer {
            employeeList {
                employeeID,
                firstName,
                lastName,
                birthDate
            },
            productList {
                name,
                unitPrice
            }
        }
    }
    `;

    export default {
        name: 'editor',
        data () {
            return {
                preview: '',
                editLog : [],
                config: {
                    events: {
                        'froalaEditor.contentChanged': function (e, editor) {

                        }
                    }
                },
                editorContent: 'Edit Your Content Here!',
                tmp: 'More text',
                viewer : {},
                loading: 0
            }
        },
        apollo:{
            viewer: {
                query: POSTS_QUERY,
                loadingKey: 'loading'
            }
        },
        mounted () {
            console.log("mounted editor");
            //console.log(this.editorContent);
        },
        watch :{
            editorContent () {
                console.log("from the watch");
                this.editLog.push(this.editorContent);
                axios.post('/processor', {'txt': this.editLog});
            }
    }


    }
</script>