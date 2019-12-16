<template>
  <div class="swipe">
    <FavoriteList :messageson="messageson" />
    <SwipeCards @swipeDataList="SendData($event)" />
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
      messageson: []
    }
  },
  methods: {
    SendData(message) {
      this.messageson = message
      //     console.log('messageson', this.messageson)
    }
  }
}
</script>
<style lang="scss" scoped>
.swipe {
  display: flex;
  @media (max-width: 1000px) {
    flex-direction: column;
  }
}
</style>
