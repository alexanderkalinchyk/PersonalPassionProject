<template>
  <button v-if="githubAuth" class="btn btn-dark ml-auto" type="button" @click="login">
    {{ $t('login_with') }}
    <fa :icon="['fab', 'github']" />
  </button>
</template>

<script>
//github / facebook / twitter login moest hier gebeuren
export default {
  name: 'LoginWithGithub'
}
</script>
