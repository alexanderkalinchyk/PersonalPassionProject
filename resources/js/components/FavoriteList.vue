<template>
  <section class="favorites-list">
    <h2>Favorites</h2>
    <button class="btn btn-primary">Cant Decide?</button>
    <p v-if="loading">Loading...</p>
    <article
      v-if="messageson != ''"
      v-for="(message, index) in messageson"
      :key="message.id"
      class="favorite-item"
    >
      <div class="item-img">
        <img :src="message.image_url" :alt="message.name" />
      </div>
      <h3>{{ message.name }}</h3>
      <p>
        {{ Number(message.distance).toFixed(1) }} km -
        <span>{{ message.price }}</span>
      </p>
      <router-link
        :to="{ path: `/details/${message.business_id}` }"
        class="btn btn-primary"
        active-class="active"
      >
        <span @click="storeInfoFront(index)">More Info</span>
      </router-link>
      <br />
      <br />
      <button @click="showData" class="btn btn-info">Invite Friends</button>
    </article>
    <article
      v-if="!loading"
      v-for="(item, index) in favoriteItems"
      :key="item.id"
      class="favorite-item"
    >
      <div class="item-img">
        <img :src="item.image_url" :alt="item.name" />
      </div>
      <h3>{{ item.name }}</h3>
      <p>
        {{ item.distance }} km -
        <span>{{ item.price }}</span>
      </p>
      <router-link
        :to="{ path: `/details/${item.business_id}` }"
        class="btn btn-primary"
        active-class="active"
      >
        <span @click="storeInfoBack(index)">More Info</span>
      </router-link>
      <!--  <button @click="moreInfo(item.business_id)"></button> -->
      <br />
      <br />
      <button @click="showData" class="btn btn-info">Invite Friends</button>
    </article>
  </section>
</template>
<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
export default {
  props: {
    messageson: {
      default: '',
      type: Array
    }
  },
  data() {
    return {
      favoriteItems: [],
      loading: true
    }
  },
  computed: mapGetters({
    user: 'auth/user',
    info: 'favorites/info'
  }),
  mounted() {
    //console.log(this.user.id)
    axios
      .get(`/api/favorites/${this.user.id}`)
      .then(response => {
        this.favoriteItems = response.data
      })
      .finally(() => (this.loading = false))
  },
  methods: {
    storeInfoBack(index) {
      //console.log('favorite items', this.favoriteItems[index])
      this.$store.dispatch('favorites/setInfo', {
        info: this.favoriteItems[index]
      })
      //console.log('info', this.info)
    },
    storeInfoFront(index) {
      console.log('favorite items', this.messageson[index])
      this.$store.dispatch('favorites/setInfo', {
        info: this.messageson[index]
      })
      //console.log('info', this.info)
    },
    showData() {
      console.log(this.favoriteItems)
    },
    moreInfo(id) {
      console.log(id)
    }
  }
}
</script>
<style lang="scss" scoped>
.favorites-list {
  background-color: #fff;
}
.favorite-item {
  margin-top: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #000000;
}
.item-img {
  width: 8rem;
  height: 8rem;
  display: flex;
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  img {
    width: 100%;
  }
}
</style>
