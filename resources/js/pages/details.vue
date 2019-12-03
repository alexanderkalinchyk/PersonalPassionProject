<template>
  <div class="details">
    <div>
      <router-link
        :to="{ name: 'swipe' }"
        class="btn-primary"
        active-class="active"
        >Back</router-link
      >
    </div>
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
        <div style="height: 100%" class="rounded-borders card card--one">
          <div style="height: 100%">
            <img
              :src="`${details.image_url}`"
              :alt="details.name"
              class="rounded-borders"
            />
            <div class="text">
              <div class="thumbnails">
                <button v-for="photo in details.photos">
                  <img :src="photo" :alt="details.name" />
                </button>
              </div>
              <h2>
                <span>{{ details.name }}</span>
                <span>{{ details.distance }} km away</span>
                <span>$$$</span>
              </h2>
            </div>
          </div>
        </div>
      </div>
      <p v-html="details"></p>
    </section>
  </div>
</template>

<script>
import FavoriteList from '../components/FavoriteList'
import axios from 'axios'
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
      welcome: 'This is your profile',
      details: [],
      loading: true
    }
  },
  watch: {
    $route(to, from) {
      console.log('new route')
      this.getBusinesses()
    }
  },
  mounted() {
    console.log('mounted')
    console.log(this.$route.params)
    this.getBusinesses()
  },
  methods: {
    SendData(message) {
      this.messageson = message
    },
    getBusinesses() {
      console.log('id', this.$route.params.id)
      axios
        .get(`/api/businesses/${this.$route.params.id}`)
        //.then(response => console.log(response))
        .then(response => (this.details = response.data))
        .finally(() => (this.loading = false))
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
