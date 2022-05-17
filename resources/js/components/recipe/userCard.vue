<template>
    <div>
        <div class="w-full">
            <div
                v-if="recipeUser.id"
                class="relative flex flex-col justify-center bg-white py-4 lg:py-6 md:px-6 lg:rounded-3xl lg:w-64 lg:my-4 shadow-xl">
                <div
                    class="flex justify-center lg:absolute lg:rounded-full lg:shadow-xl lg:left-4 sm:-top-8">
                    <avatar-component
                        class=""
                        :image="recipeUser.avatar"
                        :size="80"
                        thumb />
                </div>
                <div class="mt-1 lg:mt-8">
                    <p
                        class="flex justify-center lg:justify-start text-xl font-semibold my-2 text-blue-400">
                        {{ recipeUser.name }}
                    </p>
                    <div
                        class="flex justify-center space-x-2 lg:justify-start items-center text-gray-500 text-base hover:text-blue-300 font-semibold">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                        <p>
                            <a
                                :href="
                                    encodeURI(
                                        $app_url +
                                            'pouzivatel/' +
                                            recipeUser.name
                                    )
                                "
                                >{{
                                    $t('recipe.recipesFromUser') +
                                    ' ' +
                                    recipeUser.name
                                }}</a
                            >
                        </p>
                    </div>
                    <div
                        v-if="recipeUser.id != authId"
                        @click="showModal = true"
                        class="flex w-full justify-center lg:justify-start items-center space-x-2 font-semibold text-gray-500 text-base hover:text-blue-300">
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
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <p>{{ $t('conversations.writeMessage') }}</p>
                    </div>
                    <modal v-if="showModal" @closeModal="showModal = false">
                        <new-message
                            v-if="authId"
                            @closeModal="showModal = false"
                            class="w-full md:w-4/5 lg:w-3/6 xl:w-3/6"
                            :user_id="recipeUser.id">
                        </new-message>
                        <auth-alert v-else />
                    </modal>

                    <div class="flex w-full justify-center lg:justify-start">
                        <followers-button
                            :disabled="!authId"
                            :recipe-user-id="recipeUser.id"
                            :auth-id="authId">
                        </followers-button>
                    </div>
                </div>
            </div>

            <div
                v-if="!recipeUser.id"
                class="relative flex flex-col justify-center bg-white py-4 md:py-6 md:px-6 sm:rounded-3xl md:w-64 md:my-4 shadow-xl">
                <div
                    class="flex justify-center md:absolute md:rounded-full md:shadow-xl md:left-4 sm:-top-8">
                    <avatar-component class="" :size="80" Thumb />
                </div>
                <div class="mt-1 md:mt-8">
                    <p
                        class="flex justify-center md:justify-start text-xl font-semibold my-2 text-blue-400">
                        {{ recipeUser }}
                    </p>
                    <span v-if="!recipeUser" class="text-gray-400 text-base">{{
                        $t('recipe.unregistredUser')
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import avatarComponent from '../basicComponents/avatarComponent.vue'
import followersButton from './followersButton.vue'
import newMessage from '../conversations/newMessage.vue'
import Modal from '../basicComponents/modal.vue'
import AuthAlert from '../basicComponents/authAlert.vue'
export default {
    components: {
        newMessage,
        followersButton,
        avatarComponent,
        Modal,
        AuthAlert,
    },
    props: {
        recipeUser: [Object, String],
        authId: Number,
    },
    data() {
        return {
            showModal: false,
        }
    },
}
</script>
