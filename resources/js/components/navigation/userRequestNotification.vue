<template>
    <div class="flex items-center py-2 text-xs">
        <div
            @click="markAsRead(notification.id)"
            :class="{
                'border-l-4 border-yellow-500': !notification.read_at,
            }"
            class="flex items-center w-full justify-start px-3">
            <div>
                <i
                    v-if="notification.data.icon === 'info'"
                    class="fas fa-info-circle text-blue-300 text-4xl p-0 m-0"></i>
                <avatar-component
                    v-if="notification.data.icon === 'avatar'"
                    :image="notification.data.user.avatar"
                    :size="40"
                    Thumb />
            </div>
            <div class="flex flex-col justify-start ml-2">
                <a v-if="notification.data.user" :href="notification.data.link">
                    <li class="list none text-base text-blue-400 font-semibold">
                        {{ stringLimiter(notification.data.user.name) }}
                    </li>
                </a>
                <li
                    v-else
                    class="list none text-base text-blue-400 font-semibold">
                    {{ $app_name }}
                </li>
                <li class="list none text-xs font-semibold text-gray-400">
                    {{ notification.data.message }}
                </li>
                <div class="my-0.5">
                    <button
                        @click="acceptRequest()"
                        class="bg-indigo-600 text-white px-4 py-1 rounded-md text-xs font-semibold mr-2">
                        Prijať
                    </button>
                    <button
                        @click="$emit('delete', notification)"
                        class="bg-red-600 text-white px-4 py-1 rounded-md text-xs font-semibold">
                        Zrušiť
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import avatarComponent from '../basicComponents/avatarComponent.vue'
export default {
    props: { notification: Object },
    components: {
        avatarComponent,
    },
    methods: {
        markAsRead(id) {
            axios.put(`${this.$app_url}notification?id=${id}`).then((res) => {
                this.notification.read_at = res.data
            })
        },
        acceptRequest() {
            axios
                .post(`${this.notification.data.accept_url}`, {
                    user_id: this.notification.data.user.id,
                })
                .then((res) => {
                    this.$emit('delete', this.notification)
                })
        },
    },
}
</script>
