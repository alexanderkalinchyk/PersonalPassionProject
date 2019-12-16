<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">{{ $t('home') }}</router-link>
        <router-link :to="{ name: 'swipe' }">{{ $t('Swipe') }}</router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">{{ $t('login') }}</router-link>
        <router-link :to="{ name: 'register' }">{{ $t('register') }}</router-link>
      </template>
    </div>
    <div class="main-logo"></div>
    <div class="get-started">
      <div class="text-center">
        <div class="title mb-4">Get Started</div>
        <div class="description row">
          <div class="col-md-12">
            <p>Fun and interactive website to find new restaurants and share with your friends. Register to start now!</p>
          </div>
        </div>
        <div class="start-buttons">
          <router-link class="btn" :to="{ name: 'login' }">{{ $t('login') }}</router-link>
          <router-link class="btn" :to="{ name: 'register' }">{{ $t('register') }}</router-link>
        </div>
      </div>
      <div class="footer">&copy; 2019 Alexander Kalinchyk</div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'basic',

  metaInfo() {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  })
}
</script>

<style lang="scss" scoped>
.main-logo {
  background: url(/images/main-logo.jpg) no-repeat;
  height: 25vh;
  margin-top: -5vh;
  margin-bottom: 0rem;
  background-position: center;
  background-size: 85vh;
  @media (max-width: 500px) {
    height: 22vh;
    margin-top: 7vh;
  }
}
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}
.get-started {
  background-color: white;
  height: 33rem;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0px 9px 63.25px -16.25px dimgrey;
  position: relative;
  display: flex;
  justify-content: center;
  @media (max-width: 1200px) {
    width: 70vw;
    height: 35rem;
  }
  @media (max-width: 500px) {
    width: 90vw;
    margin-bottom: 3rem;
  }
}
.title {
  font-size: 3rem;
  font-weight: bold;
  font-style: italic;
}
.description {
  font-size: 1.3rem;
}
.start-buttons {
  width: 100%;
  margin-top: 0rem;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  height: 17rem;
  a {
    background: #ffffff; /* Old browsers */
    background: -moz-linear-gradient(
      left,
      #ffffff -20%,
      #449a4c 100%
    ); /* FF3.6-15 */
    background: -webkit-linear-gradient(
      left,
      #ffffff -20%,
      #449a4c 100%
    ); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(
      to right,
      #ffffff -20%,
      #449a4c 100%
    ); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    display: flex;
    justify-content: center;
    padding: 0.5rem;
    font-size: 2rem;
    width: 20rem;
    border-radius: 5rem;
    color: white;
    text-transform: uppercase;
    box-shadow: 0px 9px 27.25px -16.25px dimgrey;
    transition: all 0.2s ease;
    border: 1px solid white;
    &:hover {
      border: 1px solid#a4d792;
      color: #3e3c3e;
      background: none;
      transform: translateY(4px);
    }
  }
}
.footer {
  position: absolute;
  bottom: 0rem;
  padding: 1rem;
  width: 100%;
  text-align: center;
  color: white;
  background: #a4d792;
  border-radius: 0rem 0rem 1rem 1rem;
}
</style>
