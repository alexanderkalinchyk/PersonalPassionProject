<template>
  <transition name="page" mode="out-in">
    <slot>
      <router-view />
    </slot>
  </transition>
</template>

<script>
//child component voor 2 layouts: basic en default
export default {
  name: 'Child'
}
</script>
