<template>
  <card :title="$t('your_preferences')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')" />

      <!-- Range -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{
          $t('range')
        }}</label>
        <div class="col-md-7 flex-center">
          <input
            v-model="form.range"
            :class="{ 'is-invalid': form.errors.has('range') }"
            class="custom-range slider"
            type="range"
            name="range"
            min="1"
            max="40000"
          />
          &nbsp;
          <span>{{ form.range }}</span>
          <has-error :form="form" field="range" />
        </div>
      </div>

      <!-- Location -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{
          $t('location')
        }}</label>
        <div class="col-md-7">
          <input
            v-model="form.location"
            :class="{ 'is-invalid': form.errors.has('location') }"
            class="form-control"
            type="text"
            name="location"
            placeholder="Enter your city"
          />
          <button disabled @click="getLocation()" class="btn custom-btn">
            Use my current location
          </button>
          <div v-if="errorStr">
            Sorry, but the following error occurred: {{ errorStr }}
          </div>

          <div v-if="gettingLocation">
            <i>Getting your location...</i>
          </div>

          <div v-if="location">
            Your location data is {{ location.coords.latitude }},
            {{ location.coords.longitude }}
          </div>
          <has-error :form="form" field="location" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button :loading="form.busy" type="success">
            {{ $t('update') }}
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo() {
    return { title: this.$t('settings') }
  },

  data: () => ({
    location: null,
    gettingLocation: false,
    errorStr: null,
    form: new Form({
      range: '',
      location: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  created() {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
    this.form.range = 10000
  },

  methods: {
    async update() {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    },
    getLocation() {
      /*
      zou werken als ik HTTPS hosting had in plaats van HTTP

      if (!('geolocation' in navigator)) {
        this.errorStr = 'Geolocation is not available.'
        return
      }
      this.gettingLocation = true
      // get position
      navigator.geolocation.getCurrentPosition(
        pos => {
          this.gettingLocation = false
          this.location = pos
        },
        err => {
          this.gettingLocation = false
          this.errorStr = err.message
        }
      )
      */
    }
  }
}
</script>
<style lang="scss">
.flex-center {
  display: flex;
  align-items: center;
}
.slider {
  width: 25rem;
}
.custom-btn {
  color: #007bff;
  text-decoration: none;
  background-color: transparent;
  padding-left: 0;
  &:hover {
    text-decoration: underline;
    color: #007bff;
  }
  &:disabled {
    color: grey;
  }
}
</style>
