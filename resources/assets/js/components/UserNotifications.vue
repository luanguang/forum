<template>
    <li class="dropdown" v-show="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications" :key="notification.id">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            }
        },

        created() {
            axios.get('/profile/' + window.Laravel.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profile/' + window.Laravel.user.name + '/notifications/' + notification.id);
            }
        }
    }
</script>

