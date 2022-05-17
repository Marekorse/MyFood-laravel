<template>
    <div>
        <div class="flex items-center justify-center px-4 pt-20 w-full">
            <div class="max-w-4xl bg-white w-full rounded-lg shadow-xl">
                <div class="p-4 border-b">
                    <h2 class="text-2xl">{{ $t('settings.settings') }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ $t('settings.yourAccount') }}
                    </p>
                </div>
                <div>
                    <update-input
                        @updateUser="updateUser"
                        :label="$t('settings.userName')"
                        :value="user.name"
                        name="name"></update-input>
                    <update-input
                        @updateUser="updateUser"
                        :label="$t('settings.userEmail')"
                        :value="user.email"
                        name="email"></update-input>
                    <update-input
                        @updateUser="updateUser"
                        :label="$t('settings.userStatus')"
                        :value="user.status"
                        name="status"></update-input>
                    <div
                        class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            {{ $t('settings.userPassword') }}
                        </p>
                        <a
                            @click.prevent="changePassword = true"
                            href=""
                            class="px-3 text-blue-400 font-semibold">
                            {{ $t('global.edit') }}
                        </a>
                        <password-change
                            v-if="changePassword"
                            @close="changePassword = false"></password-change>
                    </div>
                    <div
                        class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            {{ $t('settings.userRole') }}
                        </p>
                        <p class="px-3">
                            {{ user.role }}
                        </p>
                    </div>
                    <div
                        class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            {{ $t('settings.registredSince') }}
                        </p>
                        <p class="px-3">
                            {{ dateFormat(user.created_at) }}
                        </p>
                    </div>
                    <div
                        class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4">
                        <p class="text-gray-600">{{ $t('settings.photos') }}</p>
                        <div class="space-y-2">
                            <div
                                class="border-2 flex items-center p-2 rounded justify-between space-x-2">
                                <avatar-component
                                    :image="user.avatar"
                                    :size="56"
                                    showOnClick
                                    thumb />
                                <div
                                    class="flex justify-between space-x-2 truncate">
                                    <span>
                                        {{ $t('settings.profilePhoto') }}
                                    </span>
                                    <li
                                        @click="
                                            editImage(user.avatar),
                                                (imageType = 'avatar')
                                        "
                                        class="cursor-pointer list-none text-blue-500 hover:text-blue-700">
                                        {{ $t('global.edit') }}
                                    </li>
                                </div>
                            </div>

                            <div
                                class="border-2 flex items-center p-2 rounded justify-between space-x-2">
                                <image-component
                                    class="w-32 h-14 rounded-lg"
                                    showOnClick
                                    :image="user.cover"
                                    :default-image="'images/Default/cover.jpg'" />
                                <div class="space-x-2 truncate">
                                    <div
                                        class="flex justify-between space-x-2 truncate">
                                        <span>
                                            {{
                                                $t('settings.coverPhoto')
                                            }}</span
                                        >
                                        <li
                                            @click="
                                                editImage(user.cover),
                                                    (imageType = 'cover')
                                            "
                                            class="cursor-pointer list-none text-blue-500 hover:text-blue-700">
                                            {{ $t('global.edit') }}
                                        </li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <modal v-if="showModal" @closeModal="resetModalOptions()">
                <div class="bg-white rounded-lg w-11/12 md:w-4/12 z-50">
                    <div class="flex flex-col justify-center items-center p-4">
                        <div class="flex items-center w-full">
                            <svg
                                @click="resetModalOptions()"
                                class="ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18">
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                            </svg>
                        </div>
                        <div v-if="imageType === 'avatar'" class="block">
                            <image-component
                                class="rounded-lg"
                                showOnClick
                                :image="modalPhoto"
                                :default-image="'images/Default/userAvatar.svg'" />
                            <h1 class="text-center">
                                {{ $t('settings.profilePhoto') }}
                            </h1>
                        </div>
                        <div v-if="imageType === 'cover'" class="block w-full">
                            <image-component
                                class="rounded-lg"
                                showOnClick
                                :image="modalPhoto"
                                :default-image="'images/Default/cover.jpg'" />
                            <h1 class="text-center">
                                {{ $t('settings.coverPhoto') }}
                            </h1>
                        </div>

                        <div class="flex">
                            <li
                                v-if="saveOrNot"
                                @click="updateImage(imageType)"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ $t('global.save') }}
                            </li>
                            <label
                                v-else
                                for="file"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                {{ $t('global.edit') }}
                            </label>

                            <input
                                ref="image"
                                name="file"
                                type="file"
                                hidden
                                @change="handleImage"
                                id="file" />

                            <button
                                v-if="saveOrNot"
                                @click.prevent="oldImage"
                                class="ml-2 bg-transparent hover:bg-gray-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                {{ $t('global.back') }}
                            </button>
                        </div>
                    </div>
                </div>
            </modal>
        </div>
        <div
            class="w-full flex justify-center text-base text-gray-700 font-semibold p-4">
            <p class="cursor-pointer text-red-400" @click="areYouSure = true">
                {{ $t('settings.userDelete') }}
            </p>
            <are-you-sure
                v-if="areYouSure"
                @submitEvent=";(showDeleteModal = true), (areYouSure = false)"
                @closeEvent="areYouSure = false"
                :message="`Ste si naozaj istý že chcete zmazať svoj profil ? Toto rozhodnutie je trvalé a prídete a všetok obsah vášho profilu.`"
                :button="$t('global.continue')"></are-you-sure>
            <modal v-if="showDeleteModal" @closeModal="resetModalOptions()">
                <div
                    class="bg-white py-6 px-4 flex items-center justify-between rounded-lg w-full p-3 md:w-6/12">
                    <p>{{ $t('settings.userDeleteConfirmation') }} :</p>
                    <div class="px-2 border-b-2 border-green-500 relative">
                        <input
                            autofocus
                            class="w-full text-center border-none focus:ring-0"
                            type="password"
                            v-model="password"
                            id="" />
                        <div
                            class="w-full h-full absolute left-0 top-0 text-center bg-white rounded-lg p-4"
                            v-if="incorrectPassword">
                            <p
                                @click="incorrectPassword = false"
                                class="text-red-500 font-semibold">
                                {{ $t('settings.incorrectPassword') }}
                            </p>
                        </div>
                    </div>

                    <button
                        class="text-red-500 font-semibold cursor-pointer"
                        @click.prevent="deleteUser()"
                        type="submit">
                        {{ $t('global.delete') }}
                    </button>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
import avatarComponent from '../basicComponents/avatarComponent.vue'
import imageComponent from '../basicComponents/imageComponent.vue'
import passwordChange from './passwordChange.vue'
import modal from '../basicComponents/modal.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import updateInput from './updateInput.vue'
export default {
    components: {
        updateInput,
        modal,
        areYouSure,
        passwordChange,
        avatarComponent,
        imageComponent,
    },
    props: ['PropUser'],
    data: function () {
        return {
            areYouSure: false,
            showDeleteModal: false,
            user: this.PropUser,

            incorrectPassword: false,
            password: '',

            editStatus: false,
            editEmail: false,
            editName: false,
            changePassword: false,

            showModal: false,
            modalPhoto: '',
            imageType: '',
            saveOrNot: false,
            file: '',
        }
    },
    methods: {
        deleteUser() {
            let formData = new FormData()

            formData.append('password', this.password)
            formData.append('_method', 'DELETE')
            axios
                .post(`${this.$app_url}user/${this.user.id}`, formData)

                .then((res) => {
                    window.location.href = '/'
                })
                .catch((err) => {
                    this.incorrectPassword = true
                    setTimeout(() => {
                        this.incorrectPassword = false
                    }, 6000)
                })
        },
        resetModalOptions() {
            this.showModal = false
            this.showDeleteModal = false
            this.saveOrNot = false
            this.imageType = null
        },
        editImage(image) {
            this.modalPhoto = image
            this.showModal = true
        },
        handleImage() {
            this.file = this.$refs.image.files[0]
            var input = event.target

            if (input.files) {
                var reader = new FileReader()
                reader.onload = (e) => {
                    this.modalPhoto = e.target.result
                }
                reader.readAsDataURL(input.files[0])
            }
            this.saveOrNot = true
        },

        updateImage(name) {
            let formData = new FormData()

            formData.append(name, this.file)
            formData.append('_method', 'PUT')
            axios
                .post(`${this.$app_url}user/${this.user.id}`, formData)
                .then((res) => {
                    this.user = res.data
                })

            this.resetModalOptions()
        },

        updateUser(key, value) {
            let formData = new FormData()
            formData.append(key, value)
            formData.append('_method', 'PUT')
            axios
                .post(`${this.$app_url}user/${this.user.id}`, formData)
                .then((res) => (this.user = res.data))
                .catch((err) => console.log(err))
        },

        startEditing(ref) {
            this.selectText(ref)
        },
        oldImage() {
            if (this.imageType === 'avatar') {
                this.modalPhoto = this.user.avatar
            }
            if (this.imageType === 'cover') {
                this.modalPhoto = this.user.cover
            }
            this.saveOrNot = false
        },
    },
}
</script>
