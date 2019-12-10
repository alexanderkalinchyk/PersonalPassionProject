<template>
  <div class="col-md-7">
    <input
      required
      name="location"
      class="form-control"
      type="text"
      :value="value"
      @input="updateValue($event.target.value)"
    />
    <ul v-if="open" class="menu" style="width:100%">
      <li
        v-for="(suggestion, index) in matches"
        v-bind:class="{ active: isActive(index) }"
        @click="suggestionClick(index)"
      >
        <a href="#">{{ suggestion }}</a>
      </li>
    </ul>
    <span v-if="locRequired" style="color: red"
      >Location must not be empty <br
    /></span>
    <button disabled @click="getLocation()" class="btn custom-btn">
      Use my current location
    </button>
  </div>
</template>
<script>
import { debounce } from 'lodash'
import axios from 'axios'
export default {
  props: {
    value: {
      type: String,
      required: true
    },
    suggestions: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      open: false,
      current: 0,
      preferences: [],
      locRequired: false
    }
  },
  watch: {
    value: debounce(function() {
      console.log(this.value)
      if (this.value) {
        this.locRequired = false
        axios.patch(`/api/settings/preferences/updateLocation/${this.value}`)
      } else {
        this.locRequired = true
      }
    }, 500)
  },
  async mounted() {
    await axios.get(`/api/settings/preferences/get`).then(response => {
      this.preferences = response.data
      this.fillForm()
    })
  },
  computed: {
    // Filtering the suggestion based on the input
    matches() {
      return this.suggestions.filter(obj => {
        return obj.indexOf(this.value) >= 0
      })
    },
    openSuggestion() {
      return (
        this.selection !== '' && this.matches.length !== 0 && this.open === true
      )
    }
  },
  methods: {
    fillForm() {
      //this.value = this.preferences[0].location
      //this.$emit('value', this.preferences[0].location)
      this.$emit('input', this.preferences[0].location)
    },
    updateValue(value) {
      if (this.open === false) {
        this.open = true
        this.current = 0
      }
      this.$emit('input', value)
    },
    // When up pressed while suggestions are open
    // When up pressed while suggestions are open
    // For highlighting element
    isActive(index) {
      return index === this.current
    },
    // When one of the suggestion is clicked
    suggestionClick(index) {
      this.$emit('input', this.matches[index])
      this.open = false
    }
  }
}
</script>
<style lang="scss" scoped>
.menu {
  position: relative;
  top: 100%;
  left: 0;
  z-index: 1000;
  float: left;
  min-width: 10rem;
  padding: 0.5rem 0;
  margin: 0.125rem 0 0;
  font-size: 1rem;
  color: #212529;
  text-align: left;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.125rem;
  height: 5rem;
  overflow: hidden;
}
</style>
