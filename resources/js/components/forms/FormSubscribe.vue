<template>
    <form method="POST" action="/subscribe" class="form" @submit="onSubmit" autocomplete="off">
        <input type="hidden" name="_token" :value="csrf">
        <div class="form-group">
            <label for="assignment_code">Enter your assignment code</label>
            <input id="assignment_code" name="assignment_code" type="text" placeholder="E.g.: XT45EWCS" class="form-control" v-model="assignment_code" maxlength="8">
        </div>
        <button type="submit" class="btn btn-primary" :disabled="isDisabled">Create document</button>
    </form>
</template>
<script>
    export default {
        props: ['csrf'],
        data() {
            return {
                assignment_code: ''
            };
        },
        methods: {
            onSubmit() {
                if (!this.assignment_code) {
                    return false;
                }
                window.trackEvent('Document', 'create', this.assignment_code);
            },
        },
        computed: {
            isDisabled() {
                return !this.assignment_code;
            }
        }
    }
</script>
