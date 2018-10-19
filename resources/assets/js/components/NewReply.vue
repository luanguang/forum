<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body" id="body" rows="5" class="form-control" placehoder="说点什么吧..." required v-model="body"></textarea>
            </div>

            <button type="submit" class="btn btn-default" @click="addReply">
                提交
            </button>
        </div>

        <p class="text-center" v-else>
            请先<a href="/login">登录</a>，然后再发表回复 
        </p>
        
    </div>
</template>

<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
            };
        },

        computed: {
            signedIn() {
                return window.Laravel.signedIn;
            }
        },

        methods: {
            // addReply() {
            //     axios.post(this.endpoint, { body: this.body })
            //     .then(({data}) => {
            //         this.body = '';

            //         flash('Your reply has been posted.');

            //         this.$emit('created', data);
            //     });
            // }
            addReply() {
                axios.post(this.endpoint, { body:this.body })
                    .then(({data}) => {
                       this.body = '';

                       flash('Your reply has been posted.');

                       this.$emit('created', data);
                    });
            }
        }
    }
</script>

