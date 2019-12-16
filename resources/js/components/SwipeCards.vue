<template>
  <section class="container">
    <div
      v-if="loading"
      class="loading-cards fixed fixed--center"
      style="z-index: 4; color: black; text-align:center;"
    >
      <h2>Loading...</h2>
    </div>
    <div
      v-if="loading == false && businesses == ''"
      class="loading-cards fixed fixed--center"
      style="z-index: 4; color: black; text-align:center;"
    >
      <p>
        No results, adjust your settings or increase radius to see more
        restaurants
      </p>
    </div>
    <div
      v-if="current"
      class="fixed fixed--center"
      style="z-index: 3"
      :class="{ transition: isVisible }"
    >
      <Vue2InteractDraggable
        v-if="isVisible"
        :interact-out-of-sight-x-coordinate="500"
        :interact-max-rotation="15"
        :interact-x-threshold="200"
        :interact-y-threshold="200"
        :interact-event-bus-events="interactEventBus"
        interact-lock-y-axis
        interact-block-drag-down
        @draggedRight="emitAndNext('match')"
        @draggedLeft="emitAndNext('reject')"
        class="rounded-borders card card--one"
        :key="current.id"
      >
        <div style="height: 100%">
          <img :src="`${current.image_url}`" :alt="current.name" class="rounded-borders" />
          <div class="text">
            <h2>
              <span class="h2-name">{{ current.name }}</span>
              <span v-if="current.price" class="item-price">{{ current.price }}</span>
            </h2>
            <p>{{ Number(current.distance).toFixed(1) }}m away</p>
          </div>
        </div>
      </Vue2InteractDraggable>
    </div>
    <div v-if="next" class="fixed fixed--center" style="z-index: 2">
      <div class="rounded-borders card card--two">
        <img :src="`${next.image_url}`" class="rounded-borders" />
        <div class="text">
          <h2>
            <span class="h2-name">{{ next.name }}</span>
            <span v-if="next.price" class="item-price">{{ next.price }}</span>
          </h2>
          <p>{{ Number(next.distance).toFixed(1) }}m away</p>
        </div>
      </div>
    </div>
    <div class="footer fixed">
      <div class="btn btn-choice" @click="reject">
        <div class="icon-no"></div>
      </div>
      <div class="btn btn-choice" @click="match">
        <div class="icon-yes"></div>
      </div>
    </div>
  </section>
</template>
<script>
import { Vue2InteractDraggable, InteractEventBus } from 'vue2-interact'
import axios from 'axios'
import { mapGetters } from 'vuex'

const EVENTS = {
  MATCH: 'match',
  REJECT: 'reject'
}

export default {
  name: 'SwipeCards',
  components: { Vue2InteractDraggable },
  data: function() {
    return {
      loading: true,
      businesses: [],
      user: [],
      offset: [],
      list: [],
      radius: '',
      location: '',
      categories: [],
      preferencesArray: '',
      isVisible: true,
      index: 0,
      interactEventBus: {
        draggedRight: EVENTS.MATCH,
        draggedLeft: EVENTS.REJECT
      }
    }
  },
  mounted: function() {
    this.$store.dispatch('preferences/fetchPreferences')
    this.getCurrentUser()
  },
  computed: {
    current() {
      return this.businesses[this.index]
    },
    next() {
      return this.businesses[this.index + 1]
    },
    ...mapGetters({
      message: 'favorites/info',
      preferences: 'preferences/preferences'
    })
  },
  methods: {
    getCurrentUser() {
      axios.get('/api/user').then(response => {
        this.user = response.data
        //console.log('user', this.user)
        this.getCurrentUserOffset()
      })
    },
    getCurrentUserOffset() {
      //console.log('userid offset', this.user.id)
      axios.get(`/api/offset/get/${this.user.id}`).then(response => {
        this.offset = response.data
        this.getBusinesses()
      })
    },
    getBusinesses() {
      //console.log('store', this.preferences)
      console.log('preferences', this.preferences)

      if (this.preferences != null && this.preferences.length != 0) {
        console.log('store')
        console.log('store pref', this.preferences)
        this.radius = this.preferences[0].radius
        this.location = this.preferences[0].location
        for (let i = 0; i < this.preferences.length; i++) {
          this.categories[i] = this.preferences[i].category_name
        }
      }

      let filteredCategories = this.categories.filter(function(el) {
        return el != null
      })
      //console.log(filteredCategories)
      filteredCategories = filteredCategories.join(',')

      if (!filteredCategories) {
        filteredCategories = 'food'
      }
      if (
        this.preferences.length != 0 &&
        this.preferences[0].location != null &&
        this.preferences[0].radius != null
      ) {
        axios
          .get(
            `api/businesses/${this.location}/${this.radius}/${filteredCategories}/${this.offset[0].offset}`
          )
          .then(response => (this.businesses = response.data.businesses))
          .finally(() => (this.loading = false))
      } else {
        this.loading = false
      }
    },
    match() {
      InteractEventBus.$emit(EVENTS.MATCH), console.log('click - match')
    },
    reject() {
      InteractEventBus.$emit(EVENTS.REJECT), console.log('click - reject')
    },
    emitAndNext(event) {
      console.log('swipe - ', event)
      if (event == 'match') {
        this.addToFavorites()
        this.updateOffset()
        this.postData()
      } else {
        this.updateOffset()
      }
      this.$emit(event, this.index)
      setTimeout(() => (this.isVisible = false), 200)
      setTimeout(() => {
        this.index++
        this.isVisible = true
        console.log('index', this.index)
        console.log('business length', this.businesses.length)
        if (this.index == this.businesses.length) {
          console.log('fetching new shit')
          this.index = 0
          this.businesses = []
          this.loading = true
          this.getCurrentUserOffset()
        }
      }, 200)
    },
    updateOffset() {
      //console.log(this.user.id)
      axios.post(`/api/offset/${this.user.id}`)
    },
    addToFavorites() {
      this.list.push({
        user_id: this.user.id,
        business_id: this.businesses[this.index].id,
        name: this.businesses[this.index].name,
        image_url: this.businesses[this.index].image_url,
        price: this.businesses[this.index].price,
        distance: this.businesses[this.index].distance
      })
      //console.log('data', this.list[0])
      this.$emit('swipeDataList', this.list)
      //this.list = []
    },
    postData() {
      const formData = new FormData()
      formData.set('user_id', this.user.id)
      formData.set('business_id', this.businesses[this.index].id)
      formData.set('name', this.businesses[this.index].name)
      formData.set('image_url', this.businesses[this.index].image_url)
      formData.set('price', this.businesses[this.index].price)
      formData.set('distance', this.businesses[this.index].distance)
      axios({
        method: 'post',
        url: 'api/favorites',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
      })
        .then(function(response) {
          //handle success
          console.log(response)
        })
        .catch(function(response) {
          //handle error
          console.log(response)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  background-color: #f9f9f9;
  height: 85vh;
  @media (max-width: 1000px) {
    height: 73vh;
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
  width: 24rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  justify-content: space-around;
  align-items: center;
  bottom: 2rem;
  @media (max-width: 1000px) {
    position: relative;
    left: 0%;
    transform: translateX(0%);
    width: 100%;
    margin-top: 10rem !important;
  }
}

.btn {
  position: relative;
  width: 50px;
  height: 50px;
  padding: 0.2rem;
  border-radius: 50%;
  background-color: white;
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
.btn-choice {
  display: flex;
  justify-content: center;
  align-items: center;
  @media (max-width: 1000px) {
    width: 6rem;
    height: 6rem;
  }
  @media (min-width: 1000px) {
    width: 4rem;
    height: 4rem;
  }
  @media (min-width: 1200px) {
    width: 5rem;
    height: 5rem;
  }
  @media (min-width: 1900px) {
    width: 7rem;
    height: 7rem;
  }
}
.icon-no {
  background: url('/images/no.png') no-repeat;
  height: 6rem;
  width: 6rem;
  background-position: center;
  background-size: 80%;
}
.icon-yes {
  background: url('/images/favorites_img.png') no-repeat;
  height: 6rem;
  width: 6rem;
  background-position: center;
  background-size: 80%;
}
.flex {
  display: flex;
  &--center {
    align-items: center;
    justify-content: center;
  }
}

.fixed {
  position: fixed;
  @media (max-width: 1000px) {
    position: relative;
    margin-top: 3rem;
  }
  &--center {
    left: 50%;
    transform: translate(-50%, -50%);
    @media (max-width: 1000px) {
      display: flex;
      justify-content: center;
      position: relative;
      left: 0%;
      transform: translate(0%, 0%);
    }

    @media (min-width: 1000px) {
      top: 43%;
    }
    @media (min-width: 1200px) {
      top: 38%;
    }
    @media (min-width: 1900px) {
      top: 40%;
    }
  }
}
.rounded-borders {
  border-radius: 12px 12px 0px 0px;
  border: 0.1px solid white;
}
.card {
  width: 22rem;
  color: white;
  @media (max-width: 1000px) {
    height: 25rem;
  }
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
    @media (max-width: 1000px) {
      margin-top: -24rem;
    }
    @media (min-width: 1000px) {
      transform: translate(0%, 6%);
    }
    @media (min-width: 1200px) {
      transform: translate(0%, 17%);
    }
  }
  @media (min-width: 1000px) {
    height: 47vh;
  }
  @media (min-width: 1200px) {
    height: 40vh;
  }
  @media (min-width: 1900px) {
    height: 30rem;
  }
}
.text {
  bottom: 0;
  width: 100%;
  color: #3e3c3e;
  background: white;
  border-bottom-right-radius: 12px;
  border-bottom-left-radius: 12px;
  box-shadow: 0px 3px 33.25px 1.75px rgba(154, 161, 171, 0.25);
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  & p {
    @media (min-width: 1000px) {
      font-size: 1rem;
    }
    @media (min-width: 1200px) {
      font-size: 1.5rem;
    }
  }
  & h2 {
    @media (max-width: 1000px) {
      font-size: 1.5rem;
    }
  }
  @media (max-width: 1000px) {
    height: 130px;
    padding: 0.5rem;
  }
  @media (min-width: 1000px) {
    height: 6rem;
    padding: 0;
  }
  @media (min-width: 1200px) {
    height: 10rem;
    padding: 0.5rem;
  }
}
.h2-name {
  font-weight: bold;
  @media (min-width: 1000px) {
    font-size: 1.2rem;
  }
  @media (min-width: 1200px) {
    font-size: 2rem;
  }
}
.item-price {
  background-color: #a4d792;
  padding: 0.5rem;
  color: white;
  margin: 0.5rem;
  border-radius: 5rem;
  width: 3.5rem;
  text-align: center;
  @media (min-width: 1000px) {
    font-size: 1rem;
  }
  @media (min-width: 1200px) {
    font-size: 1.3rem;
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
