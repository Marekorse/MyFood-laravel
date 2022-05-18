<template>
    <div>
        <div class="relative">
            <avatar-component
                class="absolute -bottom-10 left-2/4 -ml-16"
                :image="user.avatar"
                :size="128"
                showOnClick
                thumb />
            <image-component class="h-custom" showOnClick :image="user.cover" />
        </div>

        <div class="text-center p-4 pt-10">
            <h1
                class="text-blue-400 group rounded-md inline-flex items-center text-xl font-semibold">
                {{ user.name }}
            </h1>
            <p class="text-gray-400 text-sm font-medium">
                {{ user.status }}
            </p>
        </div>
        <div class="flex gap-3 flex-row flex-wrap justify-center">
            <button-component
                @clickEvent="showModal = true"
                :disabled="!authId || authId == user.id">
                <div
                    class="flex w-full justify-center lg:justify-start items-center space-x-2 font-semibold">
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
            </button-component>
            <button-component
                :disabled="!bookAccess || !authId"
                :link="$app_url + 'receptar/' + user.book.name">
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>
                        {{ $t('userProfile.recipeBook') }}
                    </span>
                </div>
            </button-component>
        </div>
        <div class="flex items-start flex-row justify-center my-8">
            <div class="text-center">
                <span class="font-semibold text-gray-500">
                    {{ $t('userProfile.recipesCount') }}
                </span>
                <span
                    class="block text-center font-semibold text-blue-400 text-xl">
                    {{ user.recipes_count }}
                </span>
            </div>
            <div class="mx-2 text-gradient-green-blue text-4xl">|</div>
            <followers :recipe-user-id="user.id" :auth-id="authId" />
            <div class="mx-2 text-gradient-green-blue text-4xl">|</div>
            <div class="list-none text-center">
                <span class="font-semibold text-gray-500">{{
                    $t('userProfile.avgRecipesRating')
                }}</span>
                <span
                    class="font-semibold text-xl text-blue-400 flex justify-center items-center">
                    {{ avgRating }}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-yellow-300 self-start"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </span>
            </div>
        </div>
        <modal v-if="showModal" @closeModal="showModal = false">
            <new-message
                @closeModal="showModal = false"
                class="w-full md:w-4/5 lg:w-3/6 xl:w-3/6"
                :user_id="user.id">
            </new-message>
        </modal>
        <recipes-container
            class="pb-12"
            :user-id="user.id"
            :base-url="$app_url + 'pouzivatel/' + user.name"
            :card-url="$app_url + 'recept'"></recipes-container>
    </div>
</template>

<script>
import modal from '../basicComponents/modal.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import followers from './followers.vue'
import newMessage from '../conversations/newMessage.vue'
import recipesContainer from '../recipe/recipesContainer.vue'
import imageComponent from '../basicComponents/imageComponent.vue'
import ButtonComponent from '../basicComponents/buttonComponent.vue'
export default {
    components: {
        newMessage,
        followers,
        recipesContainer,
        avatarComponent,
        imageComponent,
        ButtonComponent,
        modal,
    },
    props: {
        authId: {
            type: Number,
            default: 0,
        },
        user: {
            type: Object,
            required: true,
        },
        avgRating: {
            type: Number,
            default: 0,
        },
        bookAccess: {
            type: Boolean,
        },
    },
    data() {
        return {
            showModal: false,
        }
    },
}
</script>

<style lang="scss" scoped>
.h-custom {
    max-height: 32rem /* 384px */;
}
</style>
