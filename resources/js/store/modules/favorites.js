import * as types from '../mutation-types'

// state
export const state = {
  message: 'hello',
  test1: '',
  info: ''
}

// getters
export const getters = {
  message: state => state.message,
  test: state => state.test1,
  info: state => state.info
}

// mutations
export const mutations = {
  [types.SET_INFO] (state, { info }) {
    state.info = info
  }
}

// actions
export const actions = {
  setInfo ({ commit }, { info }) {
    commit(types.SET_INFO, { info })
  }
}
