<template>
  <div class="swipe">
    <FavoriteList :messageson="messageson" />
    <SwipeCards @sistersaid="messageSon($event)" />
  </div>
</template>

<script>
import SwipeCards from '../components/SwipeCards'
import FavoriteList from '../components/FavoriteList'
export default {
  middleware: 'auth',
  name: 'swipe',
  components: {
    SwipeCards,
    FavoriteList
  },
  data() {
    return {
      messageson: ''
    }
  },
  methods: {
    messageSon(message) {
      this.messageson = message
    }
  }
}
</script>
<style lang="scss" scoped>
.swipe {
  display: flex;
}
</style>
