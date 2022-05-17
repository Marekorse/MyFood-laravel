<template>
    <div class="">
        <div class="w-48 h-52 bg-gray-200 blur-lg rounded-2xl relative">
            <svg
                @click="$emit('selectedDate', year, month + 1, day)"
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 absolute text-white bg-green-400 cursor-pointer right-2 top-2 p-0.5 rounded-full transform transition duration-500 hover:scale-110"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            <div
                class="w-full flex items-center justify-center flex-col bg-blue-600 rounded-t-2xl">
                <div class="flex items-center">
                    <svg
                        @click="setDate(year--)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3 w-3 text-gray-200 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    <li class="text-gray-200 text-base list-none">
                        {{ year }}
                    </li>
                    <svg
                        @click="setDate(year++)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3 w-3 text-gray-200 ml-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <div class="flex items-center">
                    <svg
                        @click="setDate(month--)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3 w-3 text-gray-200 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    <li class="text-gray-200 text-base list-none">
                        {{ months[month] }}
                    </li>
                    <svg
                        @click="setDate(month++)"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3 w-3 text-gray-200 ml-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
            <div class="w-full flex flex-wrap justify-center">
                <li
                    class="text-custom-calendar flex justify-center items-center text-blue-600 text-xs list-none"
                    v-for="day in days"
                    :key="day">
                    {{ day.slice(0, 2) }}
                </li>
                <li
                    @click="selectedDate(day)"
                    v-for="(day, index) in daysRange()"
                    :key="index"
                    :class="{
                        'text-blue-400 cursor-pointer':
                            month === new Date(day).getMonth() &&
                            day != selected,
                        'text-gray-400': month != new Date(day).getMonth(),
                        'text-white bg-blue-600': day === selected,
                        'border border-blue-600':
                            day === new Date().toLocaleDateString(),
                    }"
                    class="self-start text-custom-calendar flex justify-center items-center list-none text-xs hover:text-white hover:bg-blue-600 rounded-md">
                    {{ new Date(day).getDate() }}
                </li>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            months: [
                'Január',
                'Február',
                'Marec',
                'Apríl',
                'Máj',
                'Jún',
                'júl',
                'August',
                'September',
                'Október',
                'November',
                'December',
            ],
            days: [
                'Nedeľa',
                'Pondelok',
                'Utorok',
                'Streda',
                'Štvrtok',
                'Piatok',
                'Sobota',
            ],
            day: '',
            month: '',
            year: '',
            selected: '',
        }
    },
    methods: {
        daysRange() {
            let daysRange = []
            let lastDayInMonth = new Date(this.year, this.month + 1, 0)
            let firstDayInMonth = new Date(this.year, this.month, 1)

            let end = lastDayInMonth.setDate(
                lastDayInMonth.getDate() + (6 - lastDayInMonth.getDay())
            )
            let start = firstDayInMonth.setDate(
                firstDayInMonth.getDate() - firstDayInMonth.getDay()
            )

            for (
                let date = new Date(start);
                date <= end;
                date.setDate(date.getDate() + 1)
            ) {
                daysRange.push(new Date(date).toLocaleDateString())
            }

            return daysRange
        },
        selectedDate(day) {
            if (day === this.selected) {
                this.selected = ''
            } else {
                this.selected = day
                this.day = new Date(this.selected).getDate()
            }
        },
        setDate(setDate) {
            if (this.month > 11) {
                this.month
                this.month = 0
                this.year++
            } else if (this.month < 0) {
                ;(this.month = 11), this.year--
            }
        },
    },
    created() {
        const date = new Date()
        const [month, year] = [date.getMonth(), date.getFullYear()]
        this.month = month
        this.year = year
        this.daysRange()
    },
}
</script>

<style lang="scss" scoped>
.text-custom-calendar {
    width: 1.71rem;
    height: 1.4rem;
}
.custom-margin {
    margin: 0em 0.05em;
}
</style>
