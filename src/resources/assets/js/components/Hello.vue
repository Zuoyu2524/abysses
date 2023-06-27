<template>
  <div class="Hello">
    <h1 :style="{ color: 'red' }">{{ title }}</h1>
  </div>
</template>

<script>
export default {
  mixins: [LoaderMixin],
  data() {
    return {
      title: "Hello Vue",
    };
  },
};
</script>