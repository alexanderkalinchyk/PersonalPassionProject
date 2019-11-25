<template>
  <div class="swipe">
    <h1>This is a swipe page</h1>
    <SwipeCards />
  </div>
</template>

<script>
import SwipeCards from '../components/SwipeCards'
export default {
  name: 'swipe',
  components: {
    SwipeCards
  }
}
</script>
