<template>
    <div class="">
        <dropdown
            onClick
            :fixed="width < 750"
            :hover="width > 750"
            align="right">
            <template v-slot:text>
                <span
                    v-if="notificationCount"
                    class="absolute -right-1 -top-2 w-4 h-4 rounded-full bg-red-500 z-20 text-center text-white text-xs font-semibold"
                    >{{ notificationCount }}
                </span>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 md:w-10 md:h-10 relative gradient-green-blue text-white p-1.5 font-bold rounded-full shadow-inner flex items-center custom z-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </template>
            <template v-slot:dropDown>
                <div
                    class="w-72 bg-white rounded-2xl shadow-md overflow-x-hidden"
                    :class="{
                        'h-screen pt-8': width < 750,
                        'h-96': width > 750,
                    }">
                    <h4
                        class="w-full custom-color-logoGray font-corinthia text-4xl font font-semibold text-center text-green-500">
                        {{ $t('nav.notifications.title') }}
                    </h4>
                    <div
                        class="w-full h-0.5 rounded-2xl bg-gradient-to-r from-transparent via-green-500 to-transparent"></div>
                    <li
                        v-if="notifications.data && !notifications.data.length"
                        class="w-full text-center text-sm font-semibold text-gray-400">
                        {{ $t('nav.notifications.youDontHaveNotifications') }}
                    </li>
                    <a
                        :href="$app_url + 'spravy'"
                        class="flex items-center justify-center text-sm text-gray-500 font-semibold"
                        v-if="unreadMessages">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-white bg-red-500 p-1 mr-2 rounded-full font-semibold"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                        {{ $t('nav.notifications.unreadMessages') }}
                    </a>
                    <div
                        class="w-full px-1 overflow-y-auto"
                        :class="{
                            'h-80': width > 750,
                            ' h-5/6 ': width < 750,
                        }">
                        <transition-group name="fade">
                            <div
                                v-for="notification in notifications.data"
                                :key="notification.id">
                                <request-notification
                                    v-if="notification.data.request"
                                    :notification="notification"
                                    @delete="deleteNotification" />
                                <notification
                                    v-else
                                    :notification="notification"
                                    @delete="deleteNotification">
                                </notification>
                            </div>
                        </transition-group>
                        <div
                            class="flex items-center text-base p-2 text-blue-400 font-semibold">
                            <a
                                href=""
                                @click.prevent="paginate()"
                                v-if="notifications.next_page_url"
                                class="cursor-pointer">
                                {{ $t('global.showMore') }}
                            </a>
                        </div>
                    </div>
                    <div
                        class="flex justify-center items-center text-sm text-red-500 font-semibold">
                        <a
                            href=""
                            @click.prevent="deleteAll()"
                            v-if="notifications.total > 10"
                            class="cursor-pointer">
                            {{ $t('nav.notifications.deleteAll') }}
                        </a>
                    </div>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import requestNotification from './userRequestNotification.vue'
import notification from './userNotification.vue'
import dropdown from '../basicComponents/dropdown.vue'
export default {
    components: {
        dropdown,
        notification,
        requestNotification,
    },
    props: {
        user: Object,
        width: Number,
    },
    data: function () {
        return {
            notifications: '',
            unreadMessages: null,
        }
    },
    methods: {
        paginate() {
            axios.get(`${this.notifications.next_page_url}`).then((res) => {
                this.notifications.current_page =
                    res.data.notification.current_page
                this.notifications.next_page_url =
                    res.data.notification.next_page_url
                res.data.notification.data.forEach((item) => {
                    this.notifications.data.push(item)
                })
                this.unreadMessages = res.data.unread_messages
            })
        },
        onClick(notification) {
            if (notification.data.link) {
                window.location.href = notification.data.link
            } else return
        },
        deleteNotification(notification) {
            axios
                .delete(`${this.$app_url}notification/${notification.id}`)
                .then((res) => {
                    this.notifications.data.splice(
                        this.notifications.data.indexOf(notification),
                        1
                    )
                })
        },
        deleteAll() {
            axios.delete(`${this.$app_url}notification/all`).then((res) => {
                for (let i = this.notifications.data.length; i > 0; i--) {
                    setTimeout(() => {
                        this.notifications.data.splice(
                            this.notifications.data.indexOf(
                                this.notifications.data[i]
                            ),
                            1
                        )
                    }, i * 100)
                }
            })
        },
    },
    created() {
        axios.get(`${this.$app_url}notification`).then((res) => {
            this.notifications = res.data.notification
            this.unreadMessages = res.data.unread_messages
        })
    },

    mounted() {
        this.$root.$on('seen', (data) => {
            this.unreadMessages = this.unreadMessages - data
        })

        Echo.private('App.Models.User.' + this.user.id).notification(
            (notification) => {
                if (notification.data === 'unreadMessage') {
                    return
                }
                notification.read_at = null
                this.notifications.data.splice(0, 0, notification)
            }
        )
    },

    computed: {
        notificationCount() {
            if (this.notifications.data) {
                const filtered = this.notifications.data.filter((n) => {
                    return n.read_at === null
                })
                if (this.unreadMessages) {
                    return (this.unreadNotification = filtered.length + 1)
                }
                return (this.unreadNotification = filtered.length)
            }
        },
    },
}
</script>
