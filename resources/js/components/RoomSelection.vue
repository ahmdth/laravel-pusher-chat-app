<template>
  <div class="flex justify-between items-center">
    <div>{{ selected.name }} Chat</div>
    <select v-model="selected" @change="$emit('roomchange', selected)">
      <option v-for="(room, index) in rooms" :key="index" :value="room">{{ room.name }}</option>
    </select>
  </div>
</template>
<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: ['rooms', 'currentroom'],
  data(){
    return {
      selected: null
    }
  },
  created(){
    this.selected = this.currentroom
  }
});
</script>
