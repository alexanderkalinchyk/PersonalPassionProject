<template>
  <section class="favorites-list">
    <h2>Favorites</h2>
    <button class="btn btn-primary">Cant Decide?</button>
    <p v-if="loading">Loading...</p>
    <article v-if="!loading" v-for="item in favoriteItems" :key="item.id" class="favorite-item">
      <div class="item-img">
        <img :src="item.image_url" alt="Salmon_Sushi" />
      </div>
      <h3>{{item.name}}</h3>
      <p>
        {{item.distance}} km -
        <span>{{item.price}}</span>
      </p>
      <button @click="moreInfo(item.business_id)" class="btn btn-primary">More Info</button>
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
  width: 11rem;
  height: 7rem;
  display: block;
  position: relative;
  overflow: hidden;
  img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
}
</style>
