<template>
    <div>
        <div class="relative px-4">
            <div class="flex items-center">
                <a
                    @click.prevent="showCalendar = true"
                    href=""
                    class="flex items-center text-blue-600 underline">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h- w-6 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <p>{{ $t('adminPanel.filter') }}</p>
                </a>
                <a
                    v-if="clearFilter"
                    @click.prevent="clear()"
                    href=""
                    class="text-red-500 font-semibold text-xs ml-1.5"
                    >{{ $t('adminPanel.clearFilter') }}</a
                >
            </div>
            <time
                v-if="date != $d(new Date(), 'short')"
                class="text-blue-400 text-sm font-semibold"
                >{{ $t('adminPanel.statsFor') }} {{ date }}</time
            >
            <transition name="slide-fade">
                <calendar
                    class="absolute left-4 top-2 z-10"
                    v-if="showCalendar"
                    @selectedDate="filterStats" />
            </transition>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 p-4 gap-4">
            <stats-card
                message="počet návšetvníkov"
                :stats="statistics.visitors">
                <svg
                    width="30"
                    height="30"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </stats-card>
            <stats-card message="Počet používateľov" :stats="statistics.users">
                <svg
                    width="30"
                    height="30"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    class="stroke-current text-blue-400">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </stats-card>
            <stats-card message="Počet receptov" :stats="statistics.recipes">
                <svg
                    width="30"
                    height="30"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </stats-card>
            <stats-card message="Počet Aktivít" :stats="statistics.events">
                <svg
                    width="30"
                    height="30"
                    xmlns="http://www.w3.org/2000/svg"
                    class="text-blue-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </stats-card>
        </div>
        <div
            @click="showCalendar = false"
            v-if="showCalendar"
            class="fixed left-0 top-0 min-h-screen w-screen"></div>
    </div>
</template>

<script>
import statsCard from './statsCard.vue'
import calendar from '../basicComponents/calendar.vue'

export default {
    components: { statsCard, calendar },
    data: function () {
        return {
            statistics: {},
            date: this.$d(new Date(), 'short'),
            showCalendar: false,
            clearFilter: false,
        }
    },
    methods: {
        filterStats(...data) {
            const [year, month, day] = data
            axios
                .get(
                    `${this.$app_url}admin/stats?year=${year}&month=${month}&day=${day}`
                )
                .then((response) => {
                    if (!day) {
                        this.date = this.$d(
                            new Date(year, month - 1),
                            'monthOnly'
                        )
                    } else {
                        this.date = this.$d(
                            new Date(year, month - 1, day),
                            'short'
                        )
                    }
                    this.clearFilter = true
                    this.showCalendar = false
                    this.statistics = response.data
                })
        },
        clear() {
            axios.get(`${this.$app_url}admin/stats`).then((response) => {
                ;(this.date = this.$d(new Date(), 'short')),
                    (this.clearFilter = false)
                this.statistics = response.data
            })
        },
    },
    created() {
        axios.get(`${this.$app_url}admin/stats`).then((response) => {
            this.statistics = response.data
        })
    },
}
</script>
