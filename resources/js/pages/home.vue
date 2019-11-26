<template>
  <card :title="$t('home')">{{ $t('you_are_logged_in') }}</card>
</template>

<script>
import axios from 'axios'

export default {
  middleware: 'auth',
  metaInfo() {
    return { title: this.$t('home') }
  }
}
</script>
