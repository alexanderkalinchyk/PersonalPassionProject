<template>
  <section class="favorites-list">
    <h2>Favorites</h2>
    <button>Cant Decide?</button>
    <article class="favorite-item">
      <img src alt="Salmon_Sushi" />
      <h3>Salmon Sushi</h3>
      <p>Nagomi - 5km</p>
      <button class="btn btn-info">More Info</button>
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
    console.log(this.user.id)
    axios
      .get('/api/favorites')
      .then(response => {
        this.favoriteItems = response.data
      })
      .finally(() => (this.loading = false))
  },
  methods: {
    showData() {
      console.log(this.favoriteItems)
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
</style>
