<template>
    <div>
        <div
            class="relative flex items-center flex-shrink-0 justify-between px-3 py-2 bg-white rounded-lg m-1 shadow-md cursor-pointer"
            :class="{
                'bg-gradient-to-br from-transparent via-blue-100 to-blue-200':
                    selected,
            }">
            <div
            @click="$emit('getConversation', conversation)"
                class="flex items-center relative w-full  flex-shrink-0 transition duration-500 ease-in-out transform hover:translate-x-2"
                :class="{ 'transform translate-x-2': selected }">
                <avatar-component
                    class="mr-1"
                    :image="user.avatar"
                    :size="60"
                    thumb />
                <div>
                    <div class="flex flex-col">
                        <h1
                            class="font-semibold text-gray-700 text-sm break-words">
                            {{ stringLimiter(user.name, 21) }}
                        </h1>
                        <span class="text-blue-500 text-xs">{{
                            dateFormat(conversation.created_at)
                        }}</span>
                    </div>

                    <h3 class="text-xs text-gray-500 break-all font-semibold">
                        {{ stringLimiter(conversation.subject, 22) }}
                    </h3>
                </div>
            </div>
            <div class="absolute w-8 h-8 flex justify-center items-center top-2/4 -mt-4 right-2 rounded-full">
                <div class="relative ">
                    <svg
                        @click="show = !show"
                        xmlns="http://www.w3.org/2000/svg"
                        class="cursor-pointer h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    <div
                        v-if="conversation.messages_count"
                        class="w-6 h-6 text-base flex justify-center rounded-full bg-red-500 text-white font-semibold">
                        <span>{{ conversation.messages_count }}</span>
                    </div>

                    <transition name="slide-right">
                        <div class="absolute right-2 top-0 z-20" v-if="show">
                            <div
                                @click="areYouSure = true"
                                class="flex items-center bg-gradient-to-r from-green-400 to-green-600 text-white font-semibold rounded-lg px-2 py-1 m-1 shadow-sm z-10">
                                <span class="text-xs">{{
                                    $t('global.delete')
                                }}</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
            <div
                @click="show = false"
                v-if="show"
                class="absolute w-full h-full top-0 left-0 rounded-lg bg-gray-200 blur-md opacity-70"></div>
        </div>
        <are-you-sure
            v-if="areYouSure"
            @submitEvent="$emit('deleteConversation', conversation)"
            @closeEvent="areYouSure = false"
            :message="$t('areYouSure.deleteConversation')"
            :button="$t('global.delete')"></are-you-sure>
    </div>
</template>

<script>
import avatarComponent from '../basicComponents/avatarComponent.vue'
import AreYouSure from '../basicComponents/areYouSure.vue'
export default {
    components: {
        AreYouSure,
        avatarComponent,
    },
    props: {
        user: Object,
        conversation: Object,
        selected: Boolean,
        authId: Number,
    },
    data: function () {
        return {
            show: false,
            areYouSure: false,
            newMessage: false,
        }
    },
    methods: {
        playSound(url) {
            const audio = new Audio(url)
            audio.volume = 0.2
            audio.play()
        },
    },
    mounted() {
        Echo.private(`conversation.` + this.conversation.id).listen(
            'newMessage',
            (e) => {
                if (!this.selected && e.message.user_id !== this.authId) {
                    this.playSound(
                        this.$app_url + 'storage/sounds/notification.mp3'
                    )
                    this.conversation.messages_count++
                }
            }
        )
    },
}
</script>
