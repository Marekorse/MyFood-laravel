<template>
    <div>
        <div
            v-if="conversations"
            class="flex items-stretch h-screen pt-14 md:pt-16">
            <div
                class="h-full gradient-green-blue overflow-x-hidden overflow-y-auto"
                :class="{
                    'w-full': windowWidth < 655,
                    ' w-80 flex-none': windowWidth > 655,
                }">
                <div
                    v-if="!conversations.data.length"
                    class="flex flex-col justify-center items-center px-3 py-2 bg-white rounded-lg mx-1.5 mt-4 md:mt-1.5 shadow-md text-gray-400 font-semibold">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-10 w-10"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p>{{ $t('conversations.emptyInbox') }}</p>
                </div>
                <conversations-panel
                    v-if="conversations.data"
                    @conversationDeleted="reset()"
                    @getConversation="getConversation"
                    :conversations="conversations"
                    :auth-id="AuthUser.id"></conversations-panel>
                <div
                    v-if="conversations.next_page_url"
                    class="w-full flex justify-center p-3 font-semibold text-white underline">
                    <a @click.prevent="paginate()" href="">{{
                        $t('global.showMore')
                    }}</a>
                </div>
            </div>
            <div
                v-if="conversation.id && windowWidth < 655"
                @click="reset()"
                class="absolute left-2 top-14 w-10 h-10 rounded-full flex items-center justify-center gradient-green-blue cursor-pointer z-50">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
            </div>

            <messages-panel
                v-if="conversation.id"
                :class="{
                    'fixed left-0 top-0  h-screen w-screen pt-20 bg-gray-100 z-20':
                        windowWidth < 655,
                    'pt-6 md:px-6 h-full w-full': windowWidth > 655,
                }"
                :window-width="windowWidth"
                :key="componentKey"
                :conversation="conversation"
                :auth-id="AuthUser.id"></messages-panel>
        </div>
    </div>
</template>

<script>
import messagesPanel from './messagesPanel.vue'
import conversationsPanel from './conversationsPanel.vue'

export default {
    props: { AuthUser: Object },
    components: {
        messagesPanel,
        conversationsPanel,
    },

    data: function () {
        return {
            conversations: null,
            windowWidth: window.innerWidth,
            componentKey: 0,
            conversation: { id: null, messages: null, reciever: null },
        }
    },
    methods: {
        paginate() {
            axios.get(`${this.conversations.next_page_url}`).then((res) => {
                this.conversations.current_page = res.data.current_page
                this.conversations.next_page_url = res.data.next_page_url
                res.data.data.forEach((item) => {
                    this.conversations.data.push(item)
                })
            })
        },
        getConversation(selectedConversation) {
            if (
                this.conversation &&
                this.conversation.id === selectedConversation.id
            ) {
                return
            }
            axios
                .get(`${this.$app_url}conversation/${selectedConversation.id}`)
                .then((res) => {
                    this.$root.$emit(
                        'seen',
                        selectedConversation.messages_count
                    )
                    selectedConversation.messages_count = null
                    this.conversation.id = selectedConversation.id
                    this.conversation.messages = res.data
                    this.conversation.reciever = selectedConversation.user
                    this.forceRerender()
                })
        },
        reset() {
            this.conversation.id = null
            this.conversation.messages = null
            this.conversation.user = null
            this.forceRerender()
        },

        forceRerender() {
            this.componentKey += 1
        },

        handleResize() {
            this.windowWidth = window.innerWidth
        },
    },
    mounted() {
        window.addEventListener('resize', () => {
            this.windowWidth = window.innerWidth
        })
        Echo.private(`conversations.` + this.AuthUser.id).listen(
            'conversationDeletedByUser',
            (e) => {
                const conversation = this.conversations.data.find((conv) => {
                    return conv.id == e.conversation.id
                })
                if (conversation.id === this.conversation.id) {
                    this.conversation.reciever.pivot.deleted_at =
                        this.dateFormat(Date.now())
                } else {
                    conversation.user.pivot.deleted_at = this.dateFormat(
                        Date.now()
                    )
                }
            }
        )
    },
    created() {
        axios.get(`${this.$app_url}spravy`).then((res) => {
            this.conversations = res.data
        })
    },
    destroyed() {
        window.removeEventListener('resize')
    },
}
</script>
