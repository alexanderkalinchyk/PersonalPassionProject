<template>
  <div class="swipe">
    <h1>This is a swipe page</h1>
    <SwipeCards />
  </div>
</template>

<script>
// @ is an alias to /src
import SwipeCards from '@/components/SwipeCards.vue'

export default {
  name: 'swipe',
  components: {
    SwipeCards
  }
}
</script>
