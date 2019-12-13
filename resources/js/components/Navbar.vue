<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <router-link :to="{ name: user ? 'swipe' : 'swipe' }" class="navbar-brand">{{ appName }}</router-link>

      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarToggler"
        aria-controls="navbarToggler"
        aria-expanded="false"
      >
        <span class="navbar-toggler-icon" />
      </button>

      <div id="navbarToggler" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <locale-dropdown />
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>-->
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item d-flex align-items-center">
            <div class="notification">
              <a v-on:click="getNotifications()" class="tooltip-bell">
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M15.137 3.945c-.644-.374-1.042-1.07-1.04-1.82v-.003c0-1.172-.939-2.122-2.097-2.122s-2.097.95-2.097 2.122v.003c.002.751-.396 1.446-1.04 1.82-4.668 2.712-1.986 11.715-6.863 13.306v1.749h20v-1.749c-4.877-1.591-2.195-10.594-6.863-13.306zm-3.137-2.945c.552 0 1 .449 1 1 0 .552-.448 1-1 1s-1-.448-1-1c0-.551.448-1 1-1zm3 20c0 1.598-1.392 3-2.971 3s-3.029-1.402-3.029-3h6zm5.015-12.521c-.246-1.504-.933-3.682-2.817-5.515l1.396-1.434c1.8 1.752 2.974 4.044 3.395 6.626l-1.974.323zm-18.015-.322c.421-2.583 1.595-4.874 3.395-6.627l1.396 1.434c-1.884 1.833-2.572 4.011-2.817 5.515l-1.974-.322z"
                    />
                  </svg>
                </span>
                <span id="circle"></span>
              </a>
              <div v-if="show" class="tooltip">
                <div id="heading">
                  <div class="heading-left">
                    <h6 class="heading-title">Notifications</h6>
                  </div>
                </div>
                <div v-if="loading">
                  <p>Loading...</p>
                </div>
                <ul class="notification-list">
                  <li class="notification-item" v-for="notification in notifications">
                    <div class="user-content">
                      <p class="user-info">
                        <span class="name">{{notification.phone_number}}</span> -
                        <span class="text-primary">{{notification.restaurant_name}}</span>
                        <br />Reply Status:
                        <span
                          v-if="notification.reply == 'Accepted'"
                          class="name text-success"
                        >{{notification.reply}}</span>
                        <span
                          v-if="notification.reply == 'Declined'"
                          class="name text-danger"
                        >{{notification.reply}}</span>
                        <span
                          v-if="notification.reply == 'Pending'"
                          class="name text-warning"
                        >{{notification.reply}}</span>
                      </p>
                      <p class="time">{{notification.date}} - {{notification.time.slice(0, -3)}}</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle text-dark"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <img :src="user.photo_url" class="rounded-circle profile-photo mr-1" />
              {{ user.name }}
            </a>
            <div class="dropdown-menu">
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                <fa icon="cog" fixed-width />
                {{ $t('settings') }}
              </router-link>

              <div class="dropdown-divider" />
              <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width />
                {{ $t('logout') }}
              </a>
            </div>
          </li>
          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <router-link
                :to="{ name: 'login' }"
                class="nav-link"
                active-class="active"
              >{{ $t('login') }}</router-link>
            </li>
            <li class="nav-item">
              <router-link
                :to="{ name: 'register' }"
                class="nav-link"
                active-class="active"
              >{{ $t('register') }}</router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'
import axios from 'axios'

export default {
  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: window.config.appName,
    notifications: [],
    errors: [],
    show: false,
    loading: true
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),
  mounted() {},
  methods: {
    getNotifications() {
      this.loading = true
      this.show = !this.show
      if (this.show == true) {
        axios
          .get('/api/sms/notification/get')
          .then(response => {
            console.log('notif', response.data)
            //console.log(JSON.stringify(response.data.results))
            this.notifications = response.data
            this.loading = false
          })
          .catch(e => {
            this.errors.push(e)
          })
      }
    },
    async logout() {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style lang="scss" scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -0.375rem 0;
}
.notification {
  margin-right: 2rem;
}
.tooltip {
  &::before {
    content: '';
    position: absolute;
    top: -0.4rem;
    right: 8.3rem;
    border-left: 2rem solid transparent;
    border-right: 2rem solid transparent;
    border-bottom: 1.5rem solid #fff;
  }
  position: absolute;
  top: 2.5rem;
  line-height: 1.5;
  color: #27303d;
  width: 20rem;
  background: #fff;
  border-radius: 5px;
  -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
  opacity: 1;
}

#circle {
  position: absolute;
  top: 0;
  left: 0.75rem;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 100%;
  background: #f07379;
}
.tooltip-bell {
  position: relative;
  display: block;
}
.notification-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  display: -ms-grid;
  display: grid;
  padding: 0.65rem 0;
}

#heading {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  display: -ms-grid;
  display: grid;
  padding: 0.5rem 1rem;
  border-bottom: 0.01rem solid #eee;
}

.notification-link {
  position: absolute;
  margin: 0.4rem 0;
}

.heading-left,
.img-left {
  -ms-flex-preferred-size: auto;
  flex-basis: auto;
  -webkit-box-flex: 0;
  -ms-flex-positive: 0;
  flex-grow: 0;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -ms-grid-row: 1;
  grid-row: 1;
  margin: 0 1rem 0 0;
}
.notification-list {
  padding-left: 1rem;
}
.heading-left,
.user-content {
  grid-column: span 9;
  width: 14rem;
}

.heading-right,
.img-left {
  grid-column: auto;
}

.heading-right,
.user-content {
  -ms-flex-preferred-size: auto;
  flex-basis: auto;
  -webkit-box-flex: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
  -ms-flex-negative: 1;
  flex-shrink: 1;
  -ms-grid-row: 1;
  grid-row: 1;
}

.heading-right {
  width: 2.5rem;
}

.img-left {
  width: 3rem;
}

.user-photo {
  display: inline-block;
  vertical-align: middle;
  height: 3rem;
  width: 3rem;
  margin: 0 0.5rem 0 0;
  border-radius: 50%;
  max-width: 100%;
}

p {
  &.user-info {
    margin: 0.15rem 0 0;
  }
  &.time {
    margin: 0;
    color: #9da4ae;
  }
}

span.name {
  font-weight: 500;
}
</style>
