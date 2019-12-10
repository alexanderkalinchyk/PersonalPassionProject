<template>
  <div class="main-layout">
    <navbar />

    <div class="container">
      <child />
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Navbar
  }
}
</script>
<style lang="scss" scoped>
.container {
  max-width: 100%;
}
</style>
