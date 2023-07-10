<script>
import Hello from "./components/Hello.vue";
import ImageGallery from "./components/ImageGallery.vue";
import {LoaderMixin} from './import';
import MessagesDisplay from './MessagesDisplay.vue';

export default {
  mixins: [LoaderMixin],
  components: {
    ImageGallery,
    Hello,
    MessagesDisplay
  },
};
</script>

<template>
  <div id="app">
    <hello></hello>
    <messages-display></messages-display>
  </div>
</template>
;