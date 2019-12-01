<template>
  <section class="favorites-list">
    <h2>Favorites</h2>
    <button class="btn btn-primary">Cant Decide?</button>
    <p v-if="loading">Loading...</p>
    <article
      v-if="messageson != ''"
      v-for="message in messageson.slice().reverse()"
      :key="message.id"
      class="favorite-item"
    >
      <div class="item-img">
        <img :src="message.image_url" :alt="message.name" />
      </div>
      <h3>{{message.name}}</h3>
      <p>
        {{Number(message.distance).toFixed(1)}} km -
        <span>{{message.price}}</span>
      </p>
      <button @click="moreInfo(message.business_id)" class="btn btn-primary">More Info</button>
      <br />
      <br />
      <button @click="showData" class="btn btn-info">Invite Friends</button>
    </article>
    <article
      v-if="!loading"
      v-for="item in favoriteItems.slice().reverse()"
      :key="item.id"
      class="favorite-item"
    >
      <div class="item-img">
        <img :src="item.image_url" :alt="item.name" />
      </div>
      <h3>{{item.name}}</h3>
      <p>
        {{item.distance}} km -
        <span>{{item.price}}</span>
      </p>
      <router-link
        :to="{ name: 'details', params: { msg: 'hi' }}"
        class="btn btn-primary"
        active-class="active"
      >More Info</router-link>
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
    user: 'auth/user'
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
