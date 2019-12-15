import Vue from 'vue'

import * as VueGoogleMaps from 'vue2-google-maps'
require('dotenv').config()
Vue.use(VueGoogleMaps, {
  load: { key: process.env.VUE_APP_GOOGLE_MAPS_API_KEY }
})
Vue.component('google-map', VueGoogleMaps.Map)
