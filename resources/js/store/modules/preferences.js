import * as types from '../mutation-types'
import axios from 'axios'
// state
export const state = {
  preferences: ''
}

// getters
export const getters = {
  preferences: state => state.preferences
}

// mutations
export const mutations = {
  [types.FETCH_PREFERENCES_SUCCESS] (state, { preferences }) {
    state.preferences = preferences
  }
}

// actions
export const actions = {
  async fetchPreferences ({ commit }) {
    const { data } = await axios.get('/api/settings/preferences/get')

    commit(types.FETCH_PREFERENCES_SUCCESS, { preferences: data })
    localStorage.setItem('preferences', JSON.stringify(data))
  }
}
