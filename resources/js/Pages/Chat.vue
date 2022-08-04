<template>
  <app-layout title="Chat">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <room-selection
          v-if="currentRoom"
          :rooms="rooms"
          :currentroom="currentRoom"
          v-on:roomchange="setRoom($event)"
        />
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-4">
            <ul>
              <li v-for="(message, index) in messages" :key="index">
                {{ message.user.name }} : {{ message.message }}
              </li>
            </ul>
          </div>
          <div class="w-full flex p-4 space-x-4">
            <input
              type="text"
              v-model="message"
              @keyup.enter="sendMessage()"
              class="flex-1"
            />
            <button
              class="px-4 py-3 bg-indigo-600 text-white text-sm font-semibold"
              type="submit"
              @click="sendMessage()"
            >
              Send
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import RoomSelection from "../components/RoomSelection.vue";

export default defineComponent({
  components: {
    AppLayout,
    RoomSelection,
  },
  data() {
    return {
      message: "",
      rooms: [],
      currentRoom: null,
      messages: [],
    };
  },
  watch: {
    currentRoom() {
      this.connect();
    },
  },
  async created() {
    await this.getRooms();
    await this.getMessages();
    this.connect();
  },
  methods: {
    connect() {
      if (this.currentRoom) {
        let vm = this;
        this.getMessages();
        Echo.private(`chat.${this.currentRoom.id}`)
        .listen(".message.new", (e) => {
            vm.getMessages();
          }
        );
      }
    },
    async getRooms() {
      await axios
        .get("/chat/rooms")
        .then((res) => {
          this.rooms = res.data;
          if (this.rooms.length) {
            this.setRoom(res.data[0]);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getMessages() {
      await axios
        .get(`/chat/${this.currentRoom?.slug}/messages`)
        .then((res) => {
          this.messages = res.data;
          console.log(res);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    setRoom(room) {
      this.currentRoom = room;
    },
    sendMessage() {
      axios
        .post(`chat/${this.currentRoom.slug}/messages`, {
          message: this.message,
        })
        .then((res) => {
          this.getMessages();
          this.message = "";
        })
        .catch((error) => console.log(error.responce));
    },
  },
});
</script>
