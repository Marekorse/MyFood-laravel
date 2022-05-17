<template>
    <div>
        <div :id="'comment' + comment.id" class="flex items-center">
            <avatar-component
                class="mr-1"
                :image="comment.user.avatar"
                :size="56"
                Thumb />

            <div class="w-full">
                <div class="flex items-center">
                    <h1 class="text-blue-400 font-semibold leading-3">
                        {{ comment.user.name }}
                    </h1>
                    <span class="ml-2 text-xxs sm:text-xs">{{
                        dateFormat(comment.created_at)
                    }}</span>
                    <div class="relative">
                        <svg
                            @click="showCommentOptions = !showCommentOptions"
                            v-if="comment.user_id === authId"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-gray-600 ml-2 cursor-pointer"
                            viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                clip-rule="evenodd" />
                        </svg>
                        <transition name="fade">
                            <div
                                v-if="showCommentOptions"
                                class="absolute w-36 text-base text-gray-600 px-2 list-none cursor-pointer font-medium border-gray-300 border-2 top-4 right-1 rounded-xl bg-white p-2 shadow-md">
                                <li
                                    @click="editComment"
                                    class="ml-1 transform hover:translate-x-1 hover:scale-103 transition duration-500 ease-in-out">
                                    {{ $t('global.edit') }}
                                </li>
                                <li
                                    @click="areYouSure = true"
                                    class="ml-1 transform hover:translate-x-1 hover:scale-103 transition duration-500 ease-in-out">
                                    {{ $t('global.delete') }}
                                </li>
                            </div>
                        </transition>
                    </div>
                </div>
                <div>
                    <div class="flex w-full items-center" v-if="editing">
                        <li
                            class="cursor-pointer bg-white px-2 m-0.5 rounded-md list-none font-semibold text-blue-400 transition transform duration-500 hover:scale-105"
                            @click="updateComment(), (editing = false)">
                            {{ $t('global.save') }}
                        </li>
                        <li
                            class="cursor-pointer bg-white px-2 m-0.5 rounded-md font-semibold list-none text-red-400 transition transform duration-500 hover:scale-105"
                            @click="oldValue(), (editing = false)">
                            {{ $t('global.cancel') }}
                        </li>
                    </div>

                    <textarea
                        @input="textareaResize()"
                        ref="input"
                        rows="1"
                        v-model="text"
                        :disabled="!editing"
                        type="text"
                        maxlength="400"
                        @keydown.esc="editing = false"
                        class="text-gray-500 w-full max-w-lg hidebar bg-transparent font-semibold text-sm border-none outline-none ring-0 p-2 block whitespace-pre-line break-all focus:outline-none focus:ring-0 resize-none"></textarea>
                    <div
                        v-if="editing"
                        class="w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
                    <dropdown v-if="editing" onClick align="left">
                        <template v-slot:text
                            ><svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-gray-600 cursor-pointer"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg
                        ></template>
                        <template v-slot:dropDown>
                            <div
                                class="bg-white max-h-32 overflow-y-auto hidebar rounded-xl p-2">
                                <emojis
                                    size="sm"
                                    @addEmoji="appendEmoji"></emojis>
                            </div>
                        </template>
                    </dropdown>
                </div>
            </div>
        </div>
        <are-you-sure
            v-if="areYouSure"
            @closeEvent="areYouSure = false"
            @submitEvent="deleteComment(), (areYouSure = false)"
            :message="$t('areYouSure.deleteComment')"
            :button="$t('global.delete')"></are-you-sure>
    </div>
</template>

<script>
import dropdown from './basicComponents/dropdown.vue'
import avatarComponent from './basicComponents/avatarComponent'
import emojis from './conversations/emojis.vue'
import areYouSure from './basicComponents/areYouSure.vue'
export default {
    components: { areYouSure, emojis, dropdown, avatarComponent },
    props: { comment: Object, authId: Number },

    data: function () {
        return {
            areYouSure: false,
            editing: false,
            showCommentOptions: false,
            text: this.comment.text,
        }
    },

    methods: {
        appendEmoji(emoji) {
            this.text = this.text.concat(emoji)
        },
        editComment() {
            this.showCommentOptions = false
            this.editing = true
            this.$nextTick(() => this.$refs.input.focus())
        },
        oldValue() {
            this.text = this.comment.text
            this.$nextTick(() => {
                this.textareaResize()
            })
        },
        deleteComment() {
            axios.delete(`${this.$app_url}comments/${this.comment.id}`)
            this.$emit('deleteComment', this.comment)
        },
        updateComment() {
            if (this.text === '') {
                this.deleteComment()
                return
            }
            axios.patch(`${this.$app_url}comments/${this.comment.id}`, {
                text: this.text,
            })
            this.editing = false
        },
        textareaResize() {
            this.$refs.input.style.height = 'auto'
            this.$refs.input.style.height = this.$refs.input.scrollHeight + 'px'
        },
    },
    mounted() {
        this.textareaResize()
    },
}
</script>

<style lang="scss">
[editing='true'] {
    box-shadow: 0 2px 0 rgb(75, 102, 177);
    outline: none;
}
.text-xxs {
    font-size: 0.65rem;
}
</style>
