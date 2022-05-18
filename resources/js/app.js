window.axios = require('axios')
window._ = require('lodash')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

//-- VUE --//
import Vue from 'vue'
import linkify from 'vue-linkify'
import Echo from 'laravel-echo'
import VueInternationalization from 'vue-i18n'
import { locate } from '../lang/sk/locate.js'
import { dateTimeFormats } from '../lang/sk/dateTime.js'

Vue.use(VueInternationalization)

const i18n = new VueInternationalization({
   dateTimeFormats,
   locale: 'sk',
   messages: locate
})

Vue.mixin({
   methods: {
      containsUrl(text) {
         const urlRegex = /(https?:\/\/[^\s]+)/g
         return text.replace(urlRegex, url => {
            return `<span v-html="<a href="${url}>${url}</a>></span>"`
         })
      },
      stringLimiter: function(string, stringlimit = 14) {
         const limit = stringlimit
         if (string.length <= limit) {
            return string
         } else return (string = string.substring(0, limit) + '...')
      },
      dateFormat: function(date) {
         let dateObject = new Date(date)
         let dateString =
            dateObject.getDate() +
            '.' +
            (dateObject.getMonth() + 1) +
            ' ' +
            dateObject.getFullYear()
         return dateString
      },
      getElementRectOffset(el) {
         let bodyRect = document.body.getBoundingClientRect()
         let elem = el.getBoundingClientRect()
         let top = elem.top
         let left = elem.left
         let right = bodyRect.right - elem.right
         let bottom
         if (bodyRect.bottom <= 0) {
            bottom = bodyRect.bottom + elem.bottom
         } else bottom = bodyRect.bottom - elem.bottom
         return { top, left, right, bottom }
      }
   }
})

Vue.directive('linkified', linkify)

Vue.prototype.$app_url = process.env.MIX_APP_URL
Vue.prototype.$storage_path = process.env.MIX_APP_URL+'storage/'


import recipesContainer from './components/recipe/recipesContainer.vue'
import recipeForm from './components/recipe/recipeForm.vue'
import recipe from './components/recipe/recipe.vue'
import conversations from './components/conversations/conversations.vue'
import userSettings from './components/user/userSettings.vue'
import adminPanel from './components/admin-panel/adminPanel.vue'
import NavigationPanel from './components/navigation/navigationPanel.vue'
import appLogo from './components/navigation/logo.vue'
import verifyEmail from './components/auth/verifyEmail.vue'
import forgotPassword from './components/auth/forgotPassword.vue'
import resetPassword from './components/auth/resetPassword.vue'
import confirmPassword from './components/auth/confirmPassword.vue'
import login from './components/auth/login.vue'
import register from './components/auth/register.vue'
import userPanel from './components/user/userPanel.vue'
import leaderboard from './components/recipe/leaderboard.vue'
import recipesBook from './components/recipeBook/recipeBook.vue'
import recipeView from './components/recipeBook/recipeView.vue'

window.Pusher = require('pusher-js')

window.Echo = new Echo({
   broadcaster: 'pusher',
   key: process.env.MIX_PUSHER_APP_KEY,
   cluster: 'eu',
   forceTLS: true
})


const app = new Vue({
   el: '#app',
   i18n,
   components: {
      recipesContainer,
      conversations,
      userSettings,
      adminPanel,
      recipeForm,
      recipe,
      NavigationPanel,
      appLogo,
      verifyEmail,
      forgotPassword,
      resetPassword,
      confirmPassword,
      login,
      register,
      userPanel,
      leaderboard,
      recipesBook,
      recipeView
   }
})
