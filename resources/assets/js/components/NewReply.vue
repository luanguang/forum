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
    import 'jquery.caret';
    import 'at.js';

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

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function (usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', { body:this.body })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                       this.body = '';

                       flash('回复成功');

                       this.$emit('created', data);
                    });
            }
        }
    }
</script>

