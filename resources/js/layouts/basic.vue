<template>
  <div class="basic-layout d-flex align-items-center justify-content-center m-0">
    <child />
  </div>
</template>

<script>
export default {
  name: 'BasicLayout'
}
</script>

<style lang="scss">
.basic-layout {
  color: #3e3c3e;
  background-color: #a4d792;
  height: 100vh;
  font-weight: 100;
  position: relative;
  @media (max-width: 500px) {
    height: 100%;
  }

  .links > a {
    color: #3e3c3e;
    padding: 0 25px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.1rem;
    text-decoration: none;
    text-transform: uppercase;
  }
}
</style>
