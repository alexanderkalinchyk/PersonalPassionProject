<template>
  <section class="favorites-list">
    <h2>{{ $t('Favorites') }}</h2>
    <div class="favorites-img"></div>
    <!-- <button class="btn btn-primary">Cant Decide?</button> -->
    <p v-if="loading">Loading...</p>
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
      <p class="item-range">{{ item.distance }}m {{ $t('away') }}</p>
      <span class="item-price">{{ item.price }}</span>
      <div class="d-flex justify-content-center w-100">
        <router-link
          :to="{ path: `/friends/${item.business_id}` }"
          class="btn btn-success"
          active-class="active"
        >
          <span @click="storeInfoBack(index)">{{ $t('Invite Friends') }}</span>
        </router-link>
        <router-link
          :to="{ path: `/details/${item.business_id}` }"
          class="btn btn-primary"
          active-class="active"
        >
          <span @click="storeInfoBack(index)">{{ $t('More Info') }}</span>
        </router-link>
      </div>
      <button
        @click="deleteFavorite(item.business_id, index)"
        class="btn btn-danger"
      >{{ $t('Remove') }}</button>
    </article>
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
      <p class="item-range">{{ Number(message.distance).toFixed(1) }}m {{ $t('away') }}</p>
      <span class="item-price">{{ message.price }}</span>
      <div class="d-flex justify-content-center w-100">
        <router-link
          :to="{ path: `/friends/${message.business_id}` }"
          class="btn btn-success"
          active-class="active"
        >
          <span @click="storeInfoFront(index)">{{ $t('Invite Friends') }}</span>
        </router-link>
        <router-link
          :to="{ path: `/details/${message.business_id}` }"
          class="btn btn-primary"
          active-class="active"
        >
          <span @click="storeInfoFront(index)">{{ $t('More Info') }}</span>
        </router-link>
      </div>
      <button
        @click="deleteFavorite(message.business_id, index)"
        class="btn btn-danger"
      >{{ $t('Remove') }}</button>
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
    },
    deleteFavorite(id, index) {
      //console.log('deleting', id, index)
      this.favoriteItems.splice(index, 1)
      this.messageson.splice(index, 1)
      axios.post(`/api/favorites/delete/${id}`)
    }
  }
}
</script>
<style lang="scss" scoped>
.favorites-list {
  background-color: #fff;
  width: 20rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  & article {
    width: 100%;
  }
  & h3 {
    margin-bottom: 0;
    text-align: center;
    padding: 0.5rem;
  }
  & h2 {
    margin-top: 1rem;
  }
  @media (max-width: 1000px) {
    order: 1;
    width: 100%;
  }
}
.favorite-item {
  margin-top: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #a4d792;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.item-range {
  margin: 0;
}
.item-price {
  background-color: #a4d792;
  padding: 0.5rem;
  color: white;
  margin: 0.5rem;
  border-radius: 5rem;
  width: 3.5rem;
  text-align: center;
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
.favorites-img {
  height: 7rem;
  width: 7rem;
  background: url(/images/favorites_img.png) no-repeat;
  background-position: center;
}
.btn-primary {
  margin: 0 0.3rem;
}
.btn-success {
  margin: 0 0.3rem;
}
.btn-danger {
  margin-top: 1rem;
}
</style>
