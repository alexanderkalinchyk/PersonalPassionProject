<template>
  <div class="row"></div>
</template>

<script>
import axios from 'axios'

const qs = params =>
  Object.keys(params)
    .map(key => `${key}=${params[key]}`)
    .join('&')

export default {
  middleware: 'guest'
}
</script>
