<template>
    <div class="">
        <div class="w-full p-1 text-gray-400">
            <form action="/conversation" method="POST">
                <div
                    class="w-full flex flex-col text-gray-800 border border-gray-300 p-4 shadow-lg bg-white rounded-xl">
                    <div
                        class="heading text-left text-lg font-semibold m-1 pl-2 text-blue-400">
                        {{ $t('conversations.newMessage') }}
                    </div>
                    <div v-if="errors && errors.subject">
                        <label
                            v-for="error in errors.subject"
                            :key="error"
                            for="subject"
                            class="flex items-center text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ error }}
                        </label>
                    </div>
                    <input
                        maxlength="150"
                        v-model="subject"
                        name="subject"
                        class="title bg-gray-200 border-none p-2 mb-4 outline-none focus:ring-0 rounded-md shadow-md"
                        :placeholder="$t('conversations.subject')"
                        type="text" />
                    <div v-if="errors && errors.text">
                        <label
                            v-for="error in errors.text"
                            :key="error"
                            for="text"
                            class="flex items-center text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ error }}
                        </label>
                    </div>
                    <textarea
                        v-model="text"
                        name="text"
                        rows="6"
                        class="description bg-gray-200 border-none p-2 mb-1 outline-none focus:ring-0 rounded-xl shadow-md resize-none"
                        placeholder="Správa pre použivatela..."></textarea>
                    <div
                        class="text-gray-400 relative flex justify-start items-center w-full rounded-full mb-0.5">
                        <div @click="showEmoji = !showEmoji">
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
                        <transition name="fade">
                            <div
                                class="z-50 absolute w-full h-36 md:h-56 p-2 bg-gray-200 rounded-lg left-0 top-6 overflow-y-auto"
                                v-if="showEmoji">
                                <emoji-list
                                    @addEmoji="appendEmoji"
                                    class=""></emoji-list>
                            </div>
                        </transition>
                        <add-images
                            ref="addImages"
                            @newImages="newImages"></add-images>
                    </div>
                    <div v-if="toManyFiles">
                        <label
                            class="inline-flex items-center text-center text-xs font-semibold text-yellow-500 bg-gray-100 rounded-lg shadow-md px-4 py-0.5 border-1 border-gray-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $t('conversations.toManyFiles') }}
                        </label>
                    </div>
                    <preview-Images
                        class="max-h-28 overflow-y-auto hidebar"
                        @deleteImage="removeImage"
                        v-if="imagePreview"
                        :images="images"></preview-Images>
                    <div class="flex py-1">
                        <button
                            @click.prevent="createNewMessage"
                            type="submit"
                            class="md:text-base rounded-full border-4 border-blue-400 p-1 px-4 font-semibold cursor-pointer text-blue-400 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:text-white hover:bg-blue-400">
                            {{ $t('conversations.sendMessage') }}
                        </button>
                        <div
                            @click="reset()"
                            class="md:text-base p-1 px-4 font-semibold cursor-pointer text-gray-500 transition duration-500 ease-in-ou transform hover:-translate-y-1 hover:scale-105 hover:text-red-500">
                            {{ $t('global.cancel') }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <aprroved-modal
            :message="$t('conversations.messageWasSend')"
            v-if="approved"></aprroved-modal>
        <div
            v-if="showEmoji"
            @click="showEmoji = false"
            class="fixed left-0 top-0 w-full h-full bg-transparent opacity-80 z-40"></div>
    </div>
</template>

<script>
import aprrovedModal from '../recipe/approvedModal.vue'
import emojiList from '../conversations/emojis.vue'
import addImages from '../conversations/addImages.vue'
import previewImages from '../conversations/previewImages.vue'
import modal from '../basicComponents/modal.vue'
export default {
    components: {
        modal,
        addImages,
        emojiList,
        previewImages,
        aprrovedModal,
    },
    props: {
        disabled: Boolean,
        user_id: Number,
        small: Boolean,
    },

    data: function () {
        return {
            showEmoji: false,
            subject: '',
            text: '',
            showModal: true,
            errors: null,
            images: [],
            approved: false,
            toManyFiles: false,
            imagePreview: false,
        }
    },
    methods: {
        show() {
            if (this.disabled) {
                return
            } else this.showModal = true
        },
        newImages(newImages) {
            newImages.forEach((newImage) => {
                if (this.images.length >= 20) {
                    return (this.toManyFiles = true)
                }
                this.images.push(newImage)
            })
            this.imagePreview = true
        },
        removeImage(index) {
            if (this.images.length <= 20) {
                this.toManyFiles = false
            }
            this.images.splice(index, 1)
        },
        appendEmoji(emoji) {
            this.text = this.text.concat(emoji)
        },
        reset() {
            this.images = []
            ;(this.showEmoji = false),
                (this.subject = ''),
                (this.text = ''),
                (this.showModal = false),
                (this.errors = null),
                (this.images = []),
                (this.approved = false),
                (this.toManyFiles = false),
                (this.imagePreview = false)
            this.$emit('closeModal')
        },
        createNewMessage() {
            const formData = new FormData()
            formData.append('text', this.text)
            formData.append('subject', this.subject)
            formData.append('touser', this.user_id)
            for (let i = 0; i < this.images.length; i++) {
                formData.append('images[]', this.images[i])
            }

            axios
                .post(`${this.$app_url}conversation`, formData)
                .then((res) => {
                    this.showModal = false
                    this.approved = true
                    setTimeout(() => {
                        this.approved = false
                        this.reset()
                    }, 3000)
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                })
        },
    },
}
</script>
