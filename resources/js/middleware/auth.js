import store from '~/store'

export default async (to, from, next) => {
  // als user niet ingelogd is -> terug naar login pagina redirecten
  // anders mag de user wel door naar de pagina
  if (!store.getters['auth/check']) {
    next({ name: 'login' })
  } else {
    next()
  }
}
