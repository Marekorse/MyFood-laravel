<template>
    <div class="h-full overflow-hidden">
        <div @click="showEmoji = false" class="h-5/6 p-3 overflow-y-auto">
            <transition-group name="fade">
                <message
                    v-for="message in conversation.messages.data"
                    :key="message.id"
                    @deleteMessage="deleteMessage"
                    :ref="'message' + message.id"
                    :message="message"
                    :user-id="authId"></message>
            </transition-group>
            <div
                class="w-full text-center"
                v-if="!conversation.messages.data.length">
                <span
                    class="text-base font-semibold inline-block py-2 px-8 l bg-white text-blue-400 rounded-xl"
                    v-if="!conversation.messages.data.length">
                    {{ $t('conversations.conversationIsEmpty') }}
                </span>
            </div>

            <div ref="end" class="w-full none h-1">.</div>
        </div>
        <div class="h-1/6 pb-2 flex items-end w-full">
            <div
                v-if="deleted"
                class="self-center bg-white shadow-lg rounded-lg p-2 text-gray-600">
                <span
                    >{{ conversation.reciever.name }}
                    {{ $t('conversations.conversationWasDeleted') }}
                </span>
            </div>
            <div
                v-if="!deleted"
                class="w-full px-2 mx-1 md:mx-8 py-1 flex bg-gradient-to-br from-green-300 to-green-500 rounded-full">
                <textarea
                    ref="textarea"
                    @input="textareaResize(), (showEmoji = false)"
                    v-model="text"
                    rows="1"
                    class="w-5/6 rounded-full max-h-20 px-8 items-center bg-white outline-none border-none focus:ring-0 resize-none hidebar"
                    type="text"
                    maxlength="400"
                    :placeholder="
                        $t('conversations.messagePlaceholder')
                    "></textarea>
                <div
                    class="text-white flex flex-col self-center items-center w-10 rounded-full">
                    <div
                        class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                        @click="showEmoji = !showEmoji">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 cursor-pointer"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <transition name="fade">
                            <preview-Images
                                class="absolute -right-14 bottom-14 hidebar bg-white rounded-md shadow-sm p-4"
                                @deleteImage="removeImage"
                                v-if="images.length"
                                :images="images"></preview-Images
                        ></transition>
                        <add-images
                            ref="addImages"
                            @newImages="newImages"></add-images>
                    </div>

                    <transition name="fade">
                        <div
                            class="absolute w-full sm:w-96 p-2 bg-gray-200 rounded-lg right-0 sm:right-20 bottom-20 overflow-y-auto"
                            v-if="showEmoji">
                            <emoji-list
                                @addEmoji="appendEmoji"
                                class=""></emoji-list>
                        </div>
                    </transition>
                </div>
                <button
                    @click.prevent="sendMessage"
                    class="self-center flex items-center border-2 bg-gradient-to-b from-green-300 to-green-400 border-white rounded-full ml-2 px-4 py-1 text-white font-semibold duration-500 ease-in-out transform hover:scale-105"
                    type="submit">
                    <span class="hidden md:block">odoslať</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7 text-white pl-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import previewImages from './previewImages.vue'
import addImages from './addImages.vue'
import message from './message.vue'
import emojiList from './emojis.vue'

export default {
    components: {
        message,
        emojiList,
        addImages,
        previewImages,
    },
    props: { authId: Number, conversation: Object },

    data: function () {
        return {
            key: '',
            text: '',
            showEmoji: false,
            images: [],
        }
    },

    methods: {
        reset() {
            this.$refs.textarea.style.height = 'auto'
            this.text = ''
            this.showEmoji = false
            this.images = []
        },
        removeImage(index) {
            if (this.images.length <= 20) {
                this.toManyFiles = false
            }
            this.images.splice(index, 1)
        },
        deleteMessage(message) {
            axios
                .delete(`${this.$app_url}messages/${message.id}`)
                .then((res) => {
                    this.conversation.messages.data.splice(
                        this.conversation.messages.data.indexOf(message),
                        1
                    )
                })
        },
        newImages(images) {
            this.images = images
        },

        sendMessage() {
            const formData = new FormData()
            formData.append('conversation_id', this.conversation.id)
            formData.append('text', this.text)
            formData.append('touser', this.conversation.reciever.id)
            for (let i = 0; i < this.images.length; i++) {
                formData.append('images[]', this.images[i])
            }

            axios.post(`${this.$app_url}messages`, formData).then((res) => {
                this.reset()
            })
        },
        appendEmoji(emoji) {
            this.text = this.text.concat(emoji)
        },

        textareaResize() {
            this.$refs.textarea.style.height = 'auto'
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + 'px'

            this.$refs.end.scrollIntoView()
        },
        scrollToEnd() {
            var content = this.$refs.end
            content.scrollTop = content.scrollHeight
        },
        playSound(url) {
            const audio = new Audio(url)
            audio.volume = 0.2
            audio.play()
        },
    },
    computed: {
        deleted() {
            return this.conversation.reciever.pivot.deleted_at !== null
        },
    },

    mounted() {
        this.$refs.end.scrollIntoView()

        Echo.private(`conversation.` + this.conversation.id).listen(
            'newMessage',
            (e) => {
                this.conversation.messages.data.push(e.message)

                if (e.message.user_id !== this.authId) {
                    this.playSound(
                        this.$app_url + 'storage/sounds/notification.mp3'
                    )
                }

                setTimeout(() => {
                    this.$refs.end.scrollIntoView()
                }, 0.1)
            }
        )
    },
}
</script>

<style lang="scss">
.custom-h-90 {
    height: 90%;
}
.custom-h-10 {
    height: 10%;
}
</style>
