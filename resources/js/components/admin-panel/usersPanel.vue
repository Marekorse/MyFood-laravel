<template>
    <div class="">
        <admin-table>
            <template v-slot:title>{{ $tc('adminPanel.user', 2) }}</template>
            <template v-slot:options>
                <div
                    class="text-sm bg-white py-4 px-8 flex flex-col gap-3 rounded-lg font-semibold text-gray-600 shadow-md border border-gray-200 cursor-pointer">
                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $tc('adminPanel.user', 2) }}
                            </span>
                            <span
                                v-if="filter.emailVerified"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click="
                                    ;(filter.emailVerified = ''), paginate()
                                ">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.emailVerified === 'verified',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.emailVerified = 'verified'),
                                        paginate()
                                ">
                                {{ $tc('adminPanel.verified', 1) }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.emailVerified === 'unverified',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.emailVerified = 'unverified'),
                                        paginate()
                                ">
                                {{ $t('adminPanel.unverified') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $t('adminPanel.createdAt') }}
                            </span>
                            <span
                                v-if="filter.groupBy"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click=";(filter.groupBy = ''), paginate()">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.groupBy === 'newest',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.groupBy = 'newest'), paginate()
                                ">
                                {{ $t('adminPanel.newest') }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400':
                                        filter.groupBy === 'oldest',
                                }"
                                class="hover:text-blue-400"
                                @click="
                                    ;(filter.groupBy = 'oldest'), paginate()
                                ">
                                {{ $t('adminPanel.oldest') }}
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex gap-2 items-center">
                            <span class="text-lg">
                                {{ $t('adminPanel.role') }}
                            </span>
                            <span
                                v-if="filter.role"
                                class="hover:text-red-500 text-red-400 text-xs font-semibold"
                                @click=";(filter.role = ''), paginate()">
                                {{ $t('global.cancel') }}
                            </span>
                        </div>

                        <div
                            class="h-0.5 mt-1 w-full bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>

                        <ul
                            class="text-sm rounded-lg font-semibold text-gray-600 cursor-pointer">
                            <li
                                :class="{
                                    'text-blue-400': filter.role === 1,
                                }"
                                class="hover:text-blue-400"
                                @click=";(filter.role = 1), paginate()">
                                {{ $t('adminPanel.admin') }}
                            </li>
                            <li
                                :class="{
                                    'text-blue-400': filter.role === 2,
                                }"
                                class="hover:text-blue-400"
                                @click=";(filter.role = 2), paginate()">
                                {{ $tc('adminPanel.user', 1) }}
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
            <template v-slot:content>
                <transition-group name="fade-in" mode="out-in">
                    <div
                        @click="showUser(user)"
                        class="flex gap-4 items-center w-full flex-wrap bg-white rounded-lg m-2 p-2 cursor-pointer"
                        v-for="user in users.data"
                        :key="user.id">
                        <avatar-component
                            :size="40"
                            thumb
                            :image="user.avatar" />
                        <span class="w-72 text font-semibold text-blue-400">{{
                            stringLimiter(user.name, 35)
                        }}</span>
                        <span class="text-gray-500">{{
                            dateFormat(user.created_at)
                        }}</span>
                        <span
                            v-if="user.role_id === 1"
                            class="custom-text inline-block px-2 py-0.5 text-center text-red-400 border border-red-400 rounded-full">
                            {{ $t('adminPanel.admin') }}
                        </span>
                        <span
                            v-else
                            class="custom-text inline-block px-2 py-0.5 text-center text-blue-400 border border-blue-400 rounded-full">
                            {{ $tc('adminPanel.user', 1) }}
                        </span>
                        <span
                            v-if="user.email_verified_at"
                            class="custom-text inline-block px-2 py-0.5 text-center text-green-400 border border-green-400 rounded-full">
                            {{ $t('adminPanel.verified') }}
                        </span>
                        <span
                            v-else
                            class="custom-text inline-block px-2 py-0.5 text-center text-yellow-400 border border-yellow-400 rounded-full">
                            {{ $tc('adminPanel.unverified', 1) }}
                        </span>
                    </div>
                </transition-group>
            </template>
        </admin-table>
        <paginate
            v-if="users"
            size="medium"
            :next-page-url="users.next_page_url"
            :prev-page-url="users.prev_page_url"
            :last-page="users.last_page"
            :current-page="users.current_page"
            @paginated="paginate"></paginate>
        <div v-if="showModal">
            <modal @closeModal=";(showModal = false), reset()">
                <div
                    class="bg-white rounded-lg h-custom sm:w-full md:w-3/4 lg:w-1/2 overflow-y-auto z-50">
                    <div class="relative">
                        <div>
                            <li
                                @click="showModal = false"
                                class="list-none cursor-pointer absolute right-2 top-1 z-40">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 p-1.5 bg-white text-red-400 rounded-full"
                                    viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </li>
                        </div>
                        <div class="relative">
                            <avatar-component
                                class="absolute -bottom-10 left-2/4 -ml-16"
                                :image="selectedUser.avatar"
                                :size="128"
                                showOnClick
                                Thumb />
                            <image-component
                                class="h-96"
                                showOnClick
                                :image="selectedUser.cover" />
                        </div>
                    </div>
                    <div class="text-center p-4 pt-10">
                        <div
                            class="flex flex-col md:flex-row items-center justify-center">
                            <h1
                                class="text-blue-400 group bg-white rounded-md inline-flex items-center text-xl font-semibold">
                                <a
                                    :href="
                                        encodeURI(
                                            $app_url +
                                                'pouzivatel/' +
                                                selectedUser.name
                                        )
                                    "
                                    >{{ selectedUser.name }}</a
                                >
                            </h1>
                            <div class="list-none text-center ml-3">
                                <button-component
                                    @clickEvent="showMessageModal = true"
                                    :disabled="authId === selectedUser.id">
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
                                        <p>
                                            {{
                                                $t('conversations.writeMessage')
                                            }}
                                        </p>
                                    </div>
                                </button-component>
                                <modal
                                    v-if="showMessageModal"
                                    @closeModal="showMessageModal = false">
                                    <new-message
                                        @closeModal="showMessageModal = false"
                                        class="w-full md:w-4/5 lg:w-3/6 xl:w-3/6"
                                        :user_id="selectedUser.id">
                                    </new-message>
                                </modal>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">
                            {{ selectedUser.status }}
                        </p>
                        <div class="w-full px-4 sm:px-24 mt-3">
                            <div
                                class="flex items-center flex-col md:flex-row justify-between mb-2">
                                <p class="text-lg font-semibold text-gray-600">
                                    Emailová adresa:
                                </p>
                                <p class="text-lg font-semibold text-blue-400">
                                    {{ selectedUser.email }}
                                </p>
                            </div>
                            <div
                                class="flex items-center flex-col md:flex-row justify-between mb-2">
                                <p class="text-lg font-semibold text-gray-600">
                                    {{ $t('adminPanel.userRole') }}:
                                </p>
                                <p class="text-lg font-semibold text-blue-400">
                                    <dropdown withArrow>
                                        <template v-slot:text>
                                            <span
                                                v-if="
                                                    selectedUser.role_id == 1
                                                ">
                                                {{ $t('adminPanel.admin') }}
                                            </span>
                                            <span
                                                v-if="
                                                    selectedUser.role_id == 2
                                                ">
                                                {{ $tc('adminPanel.user', 1) }}
                                            </span>
                                        </template>
                                        <template v-slot:dropDown>
                                            <ul
                                                class="text-lg bg-gray-100 p-2 rounded-lg font-semibold text-gray-600 cursor-pointer">
                                                <li
                                                    :class="{
                                                        'text-blue-400':
                                                            selectedUser.role_id ===
                                                            1,
                                                    }"
                                                    class="hover:text-blue-400"
                                                    @click="
                                                        ;(selectedRole = 1),
                                                            (areYouSure = true)
                                                    ">
                                                    {{ $t('adminPanel.admin') }}
                                                </li>
                                                <li
                                                    :class="{
                                                        'text-blue-400':
                                                            selectedUser.role ===
                                                            2,
                                                    }"
                                                    class="hover:text-blue-400"
                                                    @click="
                                                        ;(selectedRole = 2),
                                                            (areYouSure = true)
                                                    ">
                                                    {{
                                                        $tc(
                                                            'adminPanel.user',
                                                            1
                                                        )
                                                    }}
                                                </li>
                                            </ul>
                                        </template>
                                    </dropdown>
                                    <are-you-sure
                                        v-if="areYouSure"
                                        @submitEvent="changeRole(selectedRole)"
                                        @closeEvent="areYouSure = false"
                                        :message="$t('areYouSure.changeRole')"
                                        :button="
                                            $t('adminPanel.confirm')
                                        "></are-you-sure>
                                </p>
                            </div>
                            <div
                                class="flex items-center flex-col md:flex-row justify-between mb-2">
                                <p class="text-lg font-semibold text-gray-600">
                                    {{ $t('adminPanel.registeredSince') }} :
                                </p>
                                <p class="text-lg font-semibold text-blue-400">
                                    {{ dateFormat(selectedUser.created_at) }}
                                </p>
                            </div>
                            <div
                                class="flex items-center flex-col md:flex-row justify-between mb-2">
                                <p class="text-lg font-semibold text-gray-600">
                                    {{ $t('adminPanel.verified') }}
                                </p>
                                <p
                                    v-if="selectedUser.email_verified_at"
                                    class="text-lg font-semibold text-blue-400">
                                    {{
                                        dateFormat(
                                            selectedUser.email_verified_at
                                        )
                                    }}
                                </p>
                                <p
                                    v-if="!selectedUser.email_verified_at"
                                    class="text-lg font-semibold text-red-400">
                                    {{ $t('adminPanel.notVerified') }}
                                </p>
                            </div>
                            <div
                                v-if="!confirmDelete"
                                class="flex items-center flex-col md:flex-row justify-between mb-2">
                                <p class="text-lg font-semibold text-gray-600">
                                    {{ $t('adminPanel.deleteUser') }} :
                                </p>
                                <p
                                    @click="confirmDelete = true"
                                    class="text-lg font-semibold text-red-400 cursor-pointer">
                                    {{ $t('global.delete') }}
                                </p>
                            </div>
                            <div
                                v-if="confirmDelete"
                                class="flex flex-col items-center flex-col md:flex-row justify-center mt-2 mb-2">
                                <p class="text-xs font-semibold text-gray-600">
                                    {{ $t('adminPanel.confirmDeleteUser') }}
                                </p>
                                <p class="border-b-2 border-gray-500">
                                    <input
                                        class="w-full text-center border-0 focus:outline-none focus:ring-0"
                                        v-model="deleteInput"
                                        type="text"
                                        name=""
                                        id="" />
                                </p>
                                <p
                                    v-if="deleteInput === selectedUser.name"
                                    @click="deleteUser()"
                                    class="text-lg font-semibold text-red-400 cursor-pointer">
                                    {{ $t('global.delete') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
import newMessage from '../conversations/newMessage.vue'
import areYouSure from '../basicComponents/areYouSure.vue'
import modal from '../basicComponents/modal.vue'
import paginate from '../basicComponents/paginate.vue'
import dropdown from '../basicComponents/dropdown.vue'
import avatarComponent from '../basicComponents/avatarComponent.vue'
import imageComponent from '../basicComponents/imageComponent.vue'
import buttonComponent from '../basicComponents/buttonComponent.vue'
import adminTable from './adminTable.vue'
export default {
    components: {
        buttonComponent,
        newMessage,
        paginate,
        modal,
        areYouSure,
        dropdown,
        avatarComponent,
        imageComponent,
        adminTable,
    },
    props: { authId: Number },
    data: function () {
        return {
            filter: {
                groupBy: '',
                role: '',
                emailVerified: '',
            },
            users: '',
            selectedUser: '',
            selectedRole: '',
            deleteInput: '',
            confirmDelete: false,
            areYouSure: false,
            showModal: false,
            showMessageModal: false,
        }
    },
    methods: {
        reset() {
            ;(this.deleteInput = ''),
                (this.confirmDelete = false),
                (this.showModal = false)
            this.areYouSure = false
            this.selectedRole = ''
        },
        paginate(pageNumber = 1) {
            axios
                .get(
                    `${this.$app_url}admin/users?page=${pageNumber}&role=${this.filter.role}&email=${this.filter.emailVerified}&group=${this.filter.groupBy}`
                )
                .then((response) => {
                    this.users = response.data
                })
                .catch((err) => {
                    console.log(err)
                })
        },

        changeRole(id) {
            if (this.selectedUser.id === 1) {
                alert('dobrý pokus :)')
                this.reset()
                return
            }
            axios
                .put(`${this.$app_url}admin/user/${this.selectedUser.id}`, {
                    role_id: id,
                })
                .then((response) => {
                    this.selectedUser.role_id = id
                    this.reset()
                })
        },
        showUser(user) {
            this.showModal = true
            this.selectedUser = user
        },
        deleteUser() {
            if (this.selectedUser.id === 1) {
                alert('dobrý pokus :)')
                this.reset()
                return
            }
            axios.delete(`${this.$app_url}admin/user/${this.selectedUser.id}`)
            this.users.data.splice(
                this.users.data.indexOf(this.selectedUser),
                1
            )
            this.reset()
        },
    },
    created() {
        this.paginate()
    },
}
</script>

<style lang="scss" scoped>
table {
    border-collapse: collapse;
}
.w-custom {
    width: 25rem;
}
.h-custom {
    height: 95%;
}
.h-custom-min {
    min-height: 585px;
}
.custom-text {
    font-size: 0.6rem /* 12px */;
    line-height: 1rem /* 16px */;
}
.fade-in-enter-active {
    transition: all, 0.3s;
}
.fade-in-leave-active {
    opacity: 0;
}
.fade-in-enter,
.fade-in-leave-to {
    opacity: 0;
    transform: scale(0.55);
}
</style>
