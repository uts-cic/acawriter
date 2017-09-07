<template>
    <div id="app">
        <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala>
    </div>
</template>

<script>
    import VueFroala from 'vue-froala-wysiwyg';
    Vue.use(VueFroala);

    export default {
        name: 'app',
        data () {
            return {
                config: {
                    events: {
                        'froalaEditor.keyup': function (e, editor) {
                            console.log(editor.selection.get());
                        }
                    }
                },
                editorContent: 'Edit Your Content Here!'
            }
        }
    }
</script>