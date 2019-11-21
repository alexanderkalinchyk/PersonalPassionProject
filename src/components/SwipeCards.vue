<template>
  <section class="container">
    <div
      v-if="current"
      class="fixed fixed--center"
      style="z-index: 3"
      :class="{ transition: isVisible }"
    >
      <Vue2InteractDraggable
        v-if="isVisible"
        :interact-out-of-sight-x-coordinate="500"
        :interact-max-rotation="15"
        :interact-x-threshold="200"
        :interact-y-threshold="200"
        :interact-event-bus-events="interactEventBus"
        interact-lock-y-axis
        interact-block-drag-down
        @draggedRight="emitAndNext('match')"
        @draggedLeft="emitAndNext('reject')"
        class="rounded-borders card card--one"
      >
        <div style="height: 100%">
          <img
            :src="require(`../assets/images/${current.src}`)"
            class="rounded-borders"
          />
          <div class="text">
            <h2>
              {{ current.name }},
              <span>{{ current.age }}</span>
            </h2>
          </div>
        </div>
      </Vue2InteractDraggable>
    </div>
    <div
      v-if="next"
      class="rounded-borders card card--two fixed fixed--center"
      style="z-index: 2"
    >
      <div style="height: 100%">
        <img
          :src="require(`../assets/images/${next.src}`)"
          class="rounded-borders"
        />
        <div class="text">
          <h2>
            {{ next.name }},
            <span>{{ next.age }}</span>
          </h2>
        </div>
      </div>
    </div>
    <div
      v-if="index + 2 < cards.length"
      class="rounded-borders card card--three fixed fixed--center"
      style="z-index: 1"
    >
      <div style="height: 100%"></div>
    </div>
    <div class="footer fixed">
      <div class="btn btn--decline" @click="reject">
        <i class="material-icons">X</i>
      </div>
      <div class="btn btn--like" @click="match">
        <i class="material-icons">V</i>
      </div>
    </div>
  </section>
</template>
<script>
import { Vue2InteractDraggable, InteractEventBus } from 'vue2-interact'
const EVENTS = {
  MATCH: 'match',
  REJECT: 'reject'
}

export default {
  name: 'SwipeCards',
  components: { Vue2InteractDraggable },
  data() {
    return {
      isVisible: true,
      index: 0,
      interactEventBus: {
        draggedRight: EVENTS.MATCH,
        draggedLeft: EVENTS.REJECT
      },
      cards: [
        { src: 'alexander.jpg', name: 'Pasta', age: 5 },
        { src: 'bona.jpg', name: 'Chicken', age: 3 },
        { src: 'ichi.jpg', name: 'Pork', age: 7 },
        { src: 'sushi.jpg', name: 'Sushi', age: 5 },
        { src: 'ichi2.jpg', name: 'Pork2', age: 1 },
        { src: 'bona.jpg', name: 'Chicken', age: 3 }
      ]
    }
  },
  computed: {
    current() {
      return this.cards[this.index]
    },
    next() {
      return this.cards[this.index + 1]
    }
  },
  methods: {
    match() {
      InteractEventBus.$emit(EVENTS.MATCH), console.log('click - match')
    },
    reject() {
      InteractEventBus.$emit(EVENTS.REJECT), console.log('click - reject')
    },
    emitAndNext(event) {
      console.log('swipe - ', event)
      this.$emit(event, this.index)
      setTimeout(() => (this.isVisible = false), 200)
      setTimeout(() => {
        this.index++
        this.isVisible = true
      }, 200)
    }
  }
}
</script>

<style lang="scss" scoped>
@import './src/styles/cards.scss';
</style>
