<template>
    <div>
        <ul name="list" is="transition-group">
            <div
                v-for="conversation in conversations.data"
                :key="conversation.id">
                <div>
                    <conversation-card
                    @getConversation="getConversation"
                        v-if="conversation.user"
                        @deleteConversation="conversationDelete"
                        :user="conversation.user"
                        :conversation="conversation"
                        :selected="conversation.id === selected"
                        :auth-id="authId" />
                </div>
            </div>
        </ul>
    </div>
</template>

<script>
import conversationCard from './conversationCard.vue'

export default {
    components: {
        conversationCard,
    },
    props: { conversations: Object, authId: Number },
    data: function () {
        return {
            areYouSure: false,
            selected: null,
        }
    },
    methods: {
        getConversation(conversation) {
            this.$emit('getConversation', conversation)
            this.selected = conversation.id
        },
        conversationDelete(conversation) {
            axios
                .delete(`${this.$app_url}conversation/${conversation.id}`)
                .then((res) => {
                    this.conversations.data.splice(
                        this.conversations.data.indexOf(conversation),
                        1
                    )
                    this.$emit('conversationDeleted')
                })
        },
        filterConversations() {
            return this.conversations.data.map((conversation) => {
                let filteredUser = conversation.users.find((user) => {
                    return user.id !== this.authId
                })
                return (conversation.user = filteredUser)
            })
        },
        playSound(url) {
            const audio = new Audio(url)
            audio.volume = 0.2
            audio.play()
        },
    },
    created() {
        this.filterConversations()
    },
    mounted() {
        Echo.private(`conversations.${this.authId}`).listen(
            'newConversation',
            (data) => {
                data.conversation.messages_count = 1
                this.conversations.data.splice(0, 0, data.conversation)
                this.playSound(
                    this.$app_url + 'storage/sounds/notification.mp3'
                )
                this.filterConversations()
            }
        )
    },
}
</script>

<style lang="scss" scoped>
.list-enter-active,
.list-leave-active {
    transition: all 1s;
}
.list-enter,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
