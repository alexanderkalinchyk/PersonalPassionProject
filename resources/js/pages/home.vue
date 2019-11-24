<template>
  <card :title="$t('home')">
    {{ $t('you_are_logged_in') }}
    <div v-if="loading">Loading...</div>
    <ul v-for="business in businesses" :key="business.id">
      <li>{{ business.name }}</li>
      <li>{{ business.rating }}</li>
      <img style="width:250px;" :src="business.image_url" :alt="business.alias" />
    </ul>
  </card>
</template>

<script>
import axios from 'axios'

export default {
  middleware: 'auth',
  data() {
    return {
      loading: true,
      businesses: []
    }
  },
  mounted() {
    axios
      .get('api/businesses')
      .then(response => (this.businesses = response.data.businesses))
      .finally(() => (this.loading = false))
  },
  metaInfo() {
    return { title: this.$t('home') }
  }
}
</script>
