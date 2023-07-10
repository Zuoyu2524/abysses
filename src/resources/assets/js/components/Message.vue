<template>
    <div id="messages-display" class="messages container-fluid">
      <transition-group name="scale-up">
        <message
          v-for="message in messages"
          :key="message.id"
          :id="message.id"
          :text="message.text"
          :type="message.type"
          inline-template
          v-cloak
        >
          <div
            class="messages__message alert"
            :class="typeClass"
            @mouseenter="cancelTimeout"
          >
            <a @click="close" href="#" class="close">&times;</a>
            <span>{{ text }}</span>
          </div>
        </message>
      </transition-group>
    </div>
  </template>
  
  <script>
  import Message from './Message.vue';
  
  export default {
    components: {
      Message
    },
    data() {
      return {
        messages: [
          {
            id: 1,
            text: 'Hello',
            type: 'success'
          },
          {
            id: 2,
            text: 'Hi',
            type: 'info'
          },
          {
            id: 3,
            text: 'Hola',
            type: 'warning'
          }
        ]
      };
    }
  };
  </script>
  