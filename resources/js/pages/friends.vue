<template>
  <div class="details">
    <FavoriteList :messageson="messageson" />
    <section class="container">
      <div
        v-if="loading"
        class="loading-cards fixed fixed--center"
        style="z-index: 4; color: black; text-align:center;"
      >
        <h2>Loading...</h2>
      </div>
      <div v-if="!loading" class="loading-cards fixed fixed--center">
        <div class="back-wrap">
          <router-link
            :to="{ name: 'swipe' }"
            class="btn btn-back btn-primary"
            active-class="active"
          >Back</router-link>
        </div>
        <div style="height: 100%">
          <div v-if="success" class="alert alert-success">Your invitation was successful!</div>
          <h2 class="text-center">
            Invite friends to
            <span v-if="info" class="text-primary">{{ details.name }}</span>
            <span v-else class="text-primary">{{ detailsLocal.name }}</span>
          </h2>
          <p class="text-center">
            Go out together with your friends, schedule a meeting to this place
            with a friend below:
          </p>
          <div class="col-md-12 text-center">
            <img v-if="info" :src="details.image_url" :alt="details.name" class="rounded-borders" />
            <img v-else :src="detailsLocal.image_url" :alt="details.name" class="rounded-borders" />
          </div>
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 d-flex justify-content-around">
              <span v-for="category in details.categories" class="category-tag">{{ category.title }}</span>
            </div>
            <div class="col-md-3"></div>
          </div>
          <div>
            <div class="row">
              <div class="col-md-6">
                <label for="date">Pick a Date</label>
                <input
                  type="date"
                  v-model="date"
                  class="form-control"
                  name="date"
                  id="date"
                  required
                />
              </div>
              <div class="col-md-6">
                <label for="time">Choose the time</label>
                <input
                  type="time"
                  v-model="time"
                  class="form-control"
                  name="time"
                  id="time"
                  required
                />
              </div>
            </div>
            <div class="row flex-column justify-content-center">
              <div class="col-md-12">
                <label>Enter your friend's phone number</label>
                <vue-tel-input v-model="phone" v-bind="bindProps"></vue-tel-input>
              </div>
            </div>
            <div class="row text-center">
              <div class="col-md-12 d-flex justify-content-center">
                <button @click="inviteFriend()" class="btn btn-success">Invite</button>
              </div>
              <div class="row w-100">
                <div class="col-md-12 d-flex justify-content-center">
                  <span v-if="errors" class="text-danger">Please fill in every input</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { VueTelInput } from 'vue-tel-input'
import FavoriteList from '../components/FavoriteList'
import axios from 'axios'
import { mapGetters } from 'vuex'
export default {
  name: 'favorite_details',
  components: {
    FavoriteList,
    VueTelInput
  },
  props: {
    msg: String
  },
  data() {
    return {
      messageson: [],
      details: [],
      loading: true,
      detailsLocal: '',
      phone: null,
      date: null,
      time: null,
      errors: false,
      success: false,
      bindProps: {
        mode: 'international',
        defaultCountry: 'BE',
        autocomplete: 'on',
        enabledCountryCode: true,
        name: 'telephone',
        validCharactersOnly: true
      },
      results: null
    }
  },
  computed: mapGetters({
    info: 'favorites/info'
  }),
  watch: {
    $route(to, from) {
      console.log('new route')
      this.success = false
      this.getBusinesses()
      this.storeLocaldata()
    }
  },
  mounted() {
    //console.log('mounted')
    //console.log(this.$route.params)
    this.getBusinesses()
    this.storeLocaldata()
  },
  methods: {
    inviteFriend() {
      console.log('det', this.details.name)
      console.log(this.phone)
      console.log(this.date)
      console.log(this.time)
      if (!this.phone || !this.date || !this.time) {
        this.errors = true
      } else {
        //axios.post(`/api/sms/${this.phone}`)
        axios
          .post('/api/sms/notification', {
            date: this.date,
            time: this.time,
            phone: this.phone,
            name: this.details.name,
            url: this.details.url
          })
          .then(response => {
            console.log(response),
              //console.log('user', this.user)
              (this.date = ''),
              (this.time = ''),
              (this.phone = ''),
              (this.success = true)
          })
          .catch(function(error) {
            console.log(error)
          })
      }
    },
    storeLocaldata() {
      //
      // data stored: name, distance, image_url, price
      //
      var retrievedObject
      if (this.info) {
        localStorage.setItem('detailsObject', JSON.stringify(this.info))
        console.log('hello?', this.info)
        //retrievedObject = localStorage.getItem('detailsObject')
        //   console.log('retrieved', JSON.parse(retrievedObject))
      } else {
        retrievedObject = localStorage.getItem('detailsObject')
        this.detailsLocal = JSON.parse(retrievedObject)
        console.log('else retrieved', this.detailsLocal)
        //   console.log('info1', this.detailsLocal)
      }
    },
    SendData(message) {
      this.messageson = message
    },
    getBusinesses() {
      this.loading = true
      // console.log('id', this.$route.params.id)
      if (localStorage.getItem(`${this.$route.params.id}`) == null) {
        //console.log(localStorage.getItem(`${this.$route.params.id}`))
        axios
          .get(`/api/businesses/${this.$route.params.id}`)
          //.then(response => console.log('response data', response.data))
          .then(response => {
            this.details = response.data
            localStorage.setItem(
              `${this.$route.params.id}`,
              JSON.stringify(this.details)
            )
            this.loading = false
            console.log('localstorage get')
          })
          .finally(() => (this.loading = false))
      } else {
        console.log('else')
        //console.log('id', this.$route.params.id)
        this.details = JSON.parse(
          localStorage.getItem(`${this.$route.params.id}`)
        )
        this.loading = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.details {
  display: flex;
  @media (max-width: 1000px) {
    flex-direction: column;
  }
}
.container {
  background-color: #f9f9f9;
  height: 85vh;
  @media (max-width: 1000px) {
    height: 100%;
    padding-bottom: 3rem;
    padding-top: 2rem;
  }
}
.header {
  width: 100%;
  height: 60vh;
  z-index: 0;
  top: 0;
  left: 0;
  color: white;
  text-align: center;
  font-style: italic;
  font-family: 'Engagement', cursive;
  background: #f953c6;
  background: -webkit-linear-gradient(to top, #b91d73, #f953c6);
  background: linear-gradient(to top, #b91d73, #f953c6);
  clip-path: polygon(0 1%, 100% 0%, 100% 76%, 0 89%);
  display: flex;
  justify-content: space-between;
  span {
    display: block;
    font-size: 4rem;
    padding-top: 2rem;
    text-shadow: 1px 1px 1px red;
  }
  i {
    padding: 24px;
  }
}
.footer {
  width: 20vw;
  bottom: 4vh;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  justify-content: space-around;
  align-items: center;
}
.row {
  margin-top: 1rem;
  margin-bottom: 1rem;
}
.category-tag {
  padding: 6px 15px;
  border-radius: 25px;
  white-space: nowrap;
  -webkit-tap-highlight-color: transparent;
  background-color: #12bbd4;
  color: #fff;
  margin: 0 2px;
}
.btn {
  position: relative;
  width: 15rem;
  cursor: pointer;
  transition: all 0.3s ease;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  &:active {
    transform: translateY(4px);
  }
  i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    &::before {
      content: '';
    }
  }
  &--like {
    padding: 0.5rem;
    box-shadow: 0px 3px 33.25px 1.75px rgba(154, 161, 171, 0.23);
    i {
      font-size: 32px;
    }
  }
  &--decline {
    color: dislike;
  }
  box-shadow: 0px 3px 33.25px 1.75px rgba(154, 161, 171, 0.23);
  i {
    font-size: 32px;
  }
}

.flex {
  display: flex;
  &--center {
    align-items: center;
    justify-content: center;
  }
}
.btn-back {
  width: 8rem;
  border-radius: 2rem;
}
.back-wrap {
  width: 20rem;
  margin: 1rem;
}
.fixed {
  position: fixed;
  &--center {
    left: 50%;
    top: 50vh;
    transform: translate(-50%, -50%);
    @media (max-width: 1000px) {
      left: 0;
      top: 0;
      transform: none;
    }
  }
  @media (max-width: 1000px) {
    position: relative;
  }
}
.rounded-borders {
  width: 17rem;
  border-radius: 12px 12px 12px 12px;
}
.card {
  width: 30rem;
  height: 30rem;
  color: white;
  img {
    object-fit: cover;
    display: block;
    width: 100%;
    height: 100%;
  }
  &--one {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2), 0 1px 1px rgba(0, 0, 0, 0.14),
      0 2px 1px -1px rgba(0, 0, 0, 0.12);
  }
  &--two {
    transform: translate(-48%, -48%);
    box-shadow: 0 6px 6px -3px rgba(0, 0, 0, 0.2),
      0 10px 14px 1px rgba(0, 0, 0, 0.14), 0 4px 18px 3px rgba(0, 0, 0, 0.12);
  }
  &--three {
    background: rgba(black, 0.8);
    transform: translate(-46%, -46%);
    box-shadow: 0 10px 13px -6px rgba(0, 0, 0, 0.2),
      0 20px 31px 3px rgba(0, 0, 0, 0.14), 0 8px 38px 7px rgba(0, 0, 0, 0.12);
  }
  .text {
    bottom: 0;
    width: 100%;
    color: #3e3c3e;
    background: white;
    border-bottom-right-radius: 12px;
    border-bottom-left-radius: 12px;
    padding: 0.5rem;
    span {
      font-weight: normal;
    }
  }
}
.thumbnails {
  display: flex;
  justify-content: space-around;
  button {
    width: 100px;
    height: 100px;
    background: none;
    border: none;
    outline: none;
  }
}

.transition {
  animation: appear 200ms ease-in;
}

@keyframes appear {
  from {
    transform: translate(-48%, -48%);
  }
  to {
    transform: translate(-50%, -50%);
  }
}
</style>
