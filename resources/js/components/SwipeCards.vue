<template>
  <section class="container">
    <button @click="test()"></button>
    <div
      v-if="loading"
      class="loading-cards fixed fixed--center"
      style="z-index: 4; color: black; text-align:center;"
    >
      <h2>Loading...</h2>
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
              <span>{{ current.name }}</span>
              <span>{{ Number(current.distance).toFixed(1) }}m</span>
              <span>{{ current.price }}</span>
            </h2>
          </div>
        </div>
      </Vue2InteractDraggable>
    </div>
    <div v-if="next" class="rounded-borders card card--two fixed fixed--center" style="z-index: 2">
      <div style="height: 100%">
        <img :src="`${next.image_url}`" class="rounded-borders" />
        <div class="text">
          <h2>
            {{ next.name }},
            <span>{{ Number(next.distance).toFixed(1) }}</span>,
            <span>{{ next.price }}</span>
          </h2>
        </div>
      </div>
    </div>
    <div
      v-if="index + 2 < businesses.length"
      class="rounded-borders card card--three fixed fixed--center"
      style="z-index: 1"
    >
      <div style="height: 100%"></div>
    </div>
    <div class="footer fixed">
      <div class="btn btn--decline" @click="reject">
        <i class="material-icons">X</i>
      </div>
      <div class="btn btn--like" @click="match">
        <i class="material-icons">V</i>
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
      isVisible: true,
      index: 0,
      interactEventBus: {
        draggedRight: EVENTS.MATCH,
        draggedLeft: EVENTS.REJECT
      }
    }
  },
  mounted: function() {
    this.getCurrentUser()
  },
  computed: {
    current() {
      return this.businesses[this.index]
    },
    next() {
      return this.businesses[this.index + 1]
    },
    ...mapGetters({ message: 'favorites/info' })
  },
  methods: {
    test() {
      this.$store.dispatch('favorites/setInfo', {
        info: this.businesses[this.index]
      })
      console.log(this.message)
    },
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
      axios
        .get(`api/businesses/San Francisco/10000/${this.offset[0].offset}`)
        .then(response => (this.businesses = response.data.businesses))
        .finally(() => (this.loading = false))
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

.flex {
  display: flex;
  &--center {
    align-items: center;
    justify-content: center;
  }
}

.fixed {
  position: fixed;
  &--center {
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
}
.rounded-borders {
  border-radius: 12px;
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
    position: absolute;
    bottom: 0;
    width: 100%;
    background: black;
    background: rgba(0, 0, 0, 0.5);
    border-bottom-right-radius: 12px;
    border-bottom-left-radius: 12px;
    padding: 0.5rem;
    span {
      font-weight: normal;
    }
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
