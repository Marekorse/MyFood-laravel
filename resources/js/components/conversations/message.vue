<template>
    <div>
        <div
            :class="{ 'justify-end ': message.user.id === userId }"
            :id="'message/' + message.id"
            class="flex items-center p-2">
            <div v-if="message.user_id != userId">
                <avatar-component
                    class="mr-1"
                    :image="message.user.avatar"
                    :size="40"
                    thumb />
            </div>
            <div class="flex flex-col items-start text-gray-700 max-w-sm">
                <div class="relative flex flex-col justify-end">
                    <div
                        class="flex gap-1 items-center m-0.5 flex-wrap"
                        v-if="message.images">
                        <ul
                            class="flex items-center justify-center"
                            v-for="image in message.images"
                            :key="image.id">
                            <image-component
                                class="w-28 max-h-28 rounded-lg"
                                private
                                showOnClick
                                :image="image" />
                        </ul>
                    </div>
                    <p
                        v-if="message.text"
                        v-html="message.text"
                        v-linkified:options="{
                            className:
                                'text-xs px-3 py-1 font-semibold  bg-white rounded-lg text-blue-400 underline',
                        }"
                        ref="input"
                        class="text-base inline-block self-end font-normal py-2 px-4 whitespace-pre-line break-all bg-white rounded-xl"
                        :class="{
                            'bg-green-400 list-none text-sm  text-white ':
                                message.user_id === userId,
                        }"></p>
                </div>

                <span
                    :class="{
                        '  w-full text-right text-xs text-gray-500 mr-2':
                            message.user_id === userId,
                        ' w-full text-left text-xs text-gray-500 ml-2':
                            message.user_id != userId,
                    }"
                    >{{ dateFormat(message.created_at) }}</span
                >
            </div>
            <dropdown v-if="message.user.id === userId" align="right" onClick>
                <template v-slot:text>
                    <i
                        class="fas fa-ellipsis-v text-gray-400 font-semibold text-sm p-1 w-2"></i>
                </template>
                <template v-slot:dropDown>
                    <div
                        class="w-36 text-base text-gray-600 px-2 list-none cursor-pointer font-medium border-gray-300 border-2 top-4 right-1 rounded-xl bg-white p-2 shadow-md">
                        <li
                            @click="areYouSure = true"
                            class="ml-1 transform hover:scale-103 transition duration-500 ease-in-out">
                            {{ $t('global.delete') }}
                        </li>
                    </div>
                </template>
            </dropdown>
        </div>

        <are-you-sure
            v-if="areYouSure"
            @submitEvent="
                $emit('deleteMessage', message), (showMessageOptions = false)
            "
            @closeEvent="areYouSure = false"
            :message="$t('areYouSure.deleteMessage')"
            :button="$t('global.delete')"></are-you-sure>
    </div>
</template>

<script>
import imageComponent from '../basicComponents/imageComponent.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import dropdown from '../basicComponents/dropdown.vue'
import modal from '../basicComponents/modal.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
export default {
    components: {
        areYouSure,
        modal,
        dropdown,
        avatarComponent,
        imageComponent,
    },
    props: { message: Object, userId: Number },

    data: function () {
        return {
            areYouSure: false,
            editing: false,
            showMessageOptions: false,
            selectedImage: null,
            showModal: false,
        }
    },
    methods: {
        showImage(image) {
            this.selectedImage = image.url
            this.showModal = true
        },
        hideModal() {
            this.selectedImage = null
            this.showModal = false
        },
    },
}
</script>
