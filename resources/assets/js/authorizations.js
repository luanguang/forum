let user = window.Laravel.user;

module.exports = {
    // updateReply (reply) {
    //     return reply.user_id === user.id;
    // }
    owns (model, prop = 'user_id') {
        return model[prop] == user.id;
    }
}