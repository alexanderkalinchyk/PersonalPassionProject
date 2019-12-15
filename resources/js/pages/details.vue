<template>
  <div class="details">
    <FavoriteList :messageson="messageson" />
    <section class="container">
      <div v-if="loading" style="z-index: 4; color: black; text-align:center;">
        <h2>Loading...</h2>
      </div>
      <div v-if="!loading" class="loading-cards">
        <div class="back-wrap">
          <router-link :to="{ name: 'swipe' }" class="btn btn-primary" active-class="active">Back</router-link>
        </div>
        <div style="height: 100%" class="rounded-borders card card--one">
          <div style="height: 100%">
            <img v-if="info" :src="details.image_url" :alt="details.name" class="rounded-borders" />
            <img v-else :src="detailsLocal.image_url" :alt="details.name" class="rounded-borders" />
            <div class="text">
              <div class="thumbnails">
                <button
                  v-for="photo, index in details.photos"
                  @click="selected = index; showPic($event)"
                >
                  <img :class="{highlight:index == selected}" :src="photo" :alt="details.name" />
                </button>
              </div>
              <h2 v-if="info">
                <span>{{ details.name }}</span>
                <span>{{ Number(info.distance).toFixed(1) }}m away</span>
                <span>{{ details.price }}</span>
              </h2>
              <h2 v-else>
                <span>{{ detailsLocal.name }}</span>
                <span>{{ Number(detailsLocal.distance).toFixed(1) }}m away</span>
                <span>{{ detailsLocal.price }}</span>
              </h2>
              <span v-for="(address, index) in details.location.display_address">
                {{ address }}
                <span v-if="index == 0">,</span>
              </span>
              <div>
                <span v-for="category in details.categories">{{ category.title }}</span>
              </div>
              <table>
                <tr v-if="details.hours.is_open_now == true">OPEN NOW</tr>
                <tr v-for="hour in details.hours[0].open">
                  <td>{{ hour.day }}</td>
                  <td>{{ hour.start }}</td>
                  <td>{{ hour.end }}</td>
                </tr>
              </table>

              <div>
                <a :href="details.url" target="_blank">Visit website</a>
                Call:
                <button>{{ details.display_phone }}</button>
                <div v-if="details.messaging">
                  message business:
                  <a
                    :href="details.messaging.url"
                    target="_blank"
                  >{{ details.messaging.use_case_text }}</a>
                </div>
              </div>
              <div>
                <span v-for="coordinate in details.coordinates">
                  {{
                  coordinate
                  }}
                </span>
                <button>navigate</button>
              </div>

              <div>
                <h3>Rating</h3>
                <span>{{ details.rating }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import FavoriteList from '../components/FavoriteList'
import axios from 'axios'
import { mapGetters } from 'vuex'
export default {
  name: 'favorite_details',
  components: {
    FavoriteList
  },
  props: {
    msg: String
  },
  data() {
    return {
      messageson: [],
      details: [],
      loading: true,
      selected: undefined,
      detailsLocal: ''
    }
  },
  computed: mapGetters({
    info: 'favorites/info'
  }),
  watch: {
    $route(to, from) {
      console.log('new route')
      this.getBusinesses()
      this.storeLocaldata()
      this.selected = undefined
    }
  },
  mounted() {
    //console.log('mounted')
    //console.log(this.$route.params)
    this.getBusinesses()
    this.storeLocaldata()
  },
  methods: {
    storeLocaldata() {
      //
      // store data locally to show info after page refresh
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
    },
    showPic(e) {
      console.log(e.target.src)
      console.log('details', this.details)
      console.log('detailsLocal', this.detailsLocal)
      //
      this.details.image_url = e.target.src
      if (this.detailsLocal != '') {
        this.detailsLocal.image_url = e.target.src
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.details {
  display: flex;
}
.container {
  background-color: #f9f9f9;
  height: 85vh;
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
.loading-cards {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  transform: translateY(4rem);
}
.btn {
  width: 8rem;
  border-radius: 2rem;
}
.back-wrap {
  width: 20rem;
  margin: 1rem;
}
.flex {
  display: flex;
  &--center {
    align-items: center;
    justify-content: center;
  }
}

.rounded-borders {
  border-radius: 12px 12px 0px 0px;
}
.card {
  width: 22rem;
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
.highlight {
  border: 3px solid #a4d792 !important;
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
