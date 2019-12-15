<template>
  <div class="details">
    <FavoriteList :messageson="messageson" />
    <section class="container">
      <div v-if="loading" style="z-index: 4; color: black; text-align:center;">
        <h2>Loading...</h2>
      </div>
      <div v-if="!loading" class="loading-cards">
        <div class="back-wrap">
          <router-link
            :to="{ name: 'swipe' }"
            class="btn btn-primary"
            active-class="active"
            >Back</router-link
          >
        </div>
        <div style="height: 100%" class="rounded-borders card card--one">
          <div style="height: 100%">
            <img
              v-if="info"
              :src="details.image_url"
              :alt="details.name"
              class="rounded-borders"
            />
            <img
              v-else
              :src="detailsLocal.image_url"
              :alt="details.name"
              class="rounded-borders"
            />
            <div class="text">
              <div class="thumbnails">
                <button
                  v-for="(photo, index) in details.photos"
                  @click="
                    selected = index
                    showPic($event)
                  "
                >
                  <img
                    :class="{ highlight: index == selected }"
                    :src="photo"
                    :alt="details.name"
                  />
                </button>
              </div>
              <div v-if="info">
                <h2>
                  <span class="h2-name">{{ details.name }}</span>
                  <span class="item-price">{{ details.price }}</span>
                </h2>
                <p>{{ Number(info.distance).toFixed(1) }}m away</p>
              </div>
              <div v-else>
                <h2>
                  <span class="h2-name">{{ detailsLocal.name }}</span>
                  <span class="item-price">{{ detailsLocal.price }}</span>
                </h2>
                <p>{{ Number(detailsLocal.distance).toFixed(1) }}m away</p>
              </div>
              <div class="address">
                <span
                  v-for="(address, index) in details.location.display_address"
                >
                  {{ address }}
                  <span v-if="index == 0">,</span>
                </span>
              </div>
              <div
                class="categories row d-flex justify-content-around mb-3 mt-3"
              >
                <span
                  v-for="category in details.categories"
                  class="category-tag"
                  >{{ category.title }}</span
                >
              </div>
              <table class="table">
                <thead>
                  Open Hours
                </thead>
                <tr v-if="details.hours.is_open_now == true">
                  OPEN NOW
                </tr>
                <tr v-for="hour in details.hours[0].open">
                  <td v-if="hour.day == 0" class="table-day">Monday</td>
                  <td v-if="hour.day == 1" class="table-day">Tuesday</td>
                  <td v-if="hour.day == 2" class="table-day">Wednesday</td>
                  <td v-if="hour.day == 3" class="table-day">Thursday</td>
                  <td v-if="hour.day == 4" class="table-day">Friday</td>
                  <td v-if="hour.day == 5" class="table-day">Saturday</td>
                  <td v-if="hour.day == 6" class="table-day">Sunday</td>
                  <td>{{ hour.start.replace(/(..)/g, '$1:').slice(0, -1) }}</td>
                  <td>-</td>
                  <td>{{ hour.end.replace(/(..)/g, '$1:').slice(0, -1) }}</td>
                </tr>
              </table>

              <div class="row mb-2">
                <div class="col-md-12 text-center">
                  <a :href="details.url" target="_blank">Visit website</a>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-12 text-center">
                  Call:
                  <a href="tel:details.display_phone">{{
                    details.display_phone
                  }}</a>
                </div>
              </div>
              <div class="text-center" v-if="details.messaging">
                <a :href="details.messaging.url" target="_blank">{{
                  details.messaging.use_case_text
                }}</a>
              </div>
              <div class="row mb-2">
                <div class="col-md-12 text-center">
                  <h3>
                    Location
                  </h3>
                  <GmapMap
                    :center="{
                      lat: details.coordinates.latitude,
                      lng: details.coordinates.longitude
                    }"
                    :map-type-id="mapTypeId"
                    :zoom="17"
                  >
                    <GmapMarker
                      :position="{
                        lat: details.coordinates.latitude,
                        lng: details.coordinates.longitude
                      }"
                      @click="center = position"
                    />
                  </GmapMap>
                </div>
              </div>
              <div class="row mb-2">
                <div
                  class="col-md-12 text-center d-flex flex-column justify-content-center"
                >
                  <h3>
                    Rating
                  </h3>
                  <star-rating
                    :show-rating="false"
                    read-only
                    :increment="0.5"
                    :fixed-points="1"
                    :rating="details.rating"
                  ></star-rating>
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
import FavoriteList from '../components/FavoriteList'
import axios from 'axios'
import { mapGetters } from 'vuex'
import StarRating from 'vue-star-rating'

export default {
  name: 'favorite_details',
  components: {
    FavoriteList,
    StarRating
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
      detailsLocal: '',
      center: { lat: -3.350235, lng: 111.995865 },
      mapTypeId: 'terrain',
      position: { lat: -6.9127778, lng: 107.6205556 }
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
  transform: translateY(1rem);
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
.table {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  .table-day {
    border-right: 1px solid #126ab3;
    width: 7rem;
  }
}
.vue-star-rating {
  display: flex;
  justify-content: center;
}
.rounded-borders {
  border-radius: 12px 12px 0px 0px;
}
.card {
  width: 22rem;
  height: 30rem;
  color: white;
  padding-bottom: 1rem;
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
    .h2-name {
      font-weight: bold;
      font-size: 2rem;
    }
    .item-price {
      background-color: #a4d792;
      padding: 0.5rem;
      color: white;
      margin: 0.5rem;
      border-radius: 5rem;
      width: 3.5rem;
      text-align: center;
      font-size: 1.3rem;
    }
    p {
      font-size: 1.5rem;
    }
  }
}
.categories {
  padding: 0 1rem;
}
.category-tag {
  padding: 6px 15px;
  border-radius: 25px;
  white-space: nowrap;
  -webkit-tap-highlight-color: transparent;
  background-color: #12bbd4;
  color: #fff;
}
.address {
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
  color: #8e8e8e;
}
.thumbnails {
  display: flex;
  justify-content: space-around;
  margin-bottom: 1rem;
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
.vue-map-container {
  height: 25rem;
  width: 100%;
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
