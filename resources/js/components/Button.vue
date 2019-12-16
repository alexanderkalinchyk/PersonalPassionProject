<template>
  <button
    :type="nativeType"
    :disabled="loading"
    :class="{
    [`btn-${type}`]: true,
    'btn-block': block,
    'btn-lg': large,
    'btn-loading': loading
  }"
    class="btn"
  >
    <!-- slots, van de officiele website van vuejs: Slots can contain any template code, including HTML or even other components  -->
    <slot />
  </button>
</template>

<script>
//verschillende types buttons
export default {
  name: 'VButton',

  props: {
    type: {
      type: String,
      default: 'primary'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: {
      type: Boolean,
      default: false
    },

    large: {
      type: Boolean,
      default: false
    }
  }
}
</script>
