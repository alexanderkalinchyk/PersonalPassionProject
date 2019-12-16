<template>
  <card class="text-center">
    <h3 class="mb-4">{{ $t('page_not_found') }}</h3>

    <div class="links">
      <router-link :to="{ name: 'swipe' }">{{ $t('go_home') }}</router-link>
    </div>
  </card>
</template>

<script>
//page not found 404
export default {
  name: 'NotFound'
}
</script>
