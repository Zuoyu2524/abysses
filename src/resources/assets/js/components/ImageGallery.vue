<template>
  <div class="image-gallery">
    <div class="image-container">
      <img :src="currentImage.url" :alt="currentImage.title" class="image" />
    </div>

    <div class="tag-selection">
      <h3>Select Labels:</h3>
      <div
        class="tag-category"
        v-for="category in tagCategories"
        :key="category.id"
      >
        <h4>{{ category.name }}</h4>
        <ul>
          <li v-for="tag in category.tags" :key="tag.id">
            <label>
              <input
                type="radio"
                v-model="selectedTags[category.id]"
                :value="tag.id"
              />
              {{ tag.name }}
            </label>
          </li>
        </ul>
      </div>
    </div>
    <div class="navigation-buttons">
      <button @click="previousImage" v-show="currentIndex > 0">Previous</button>
      <button
        @click="nextImage"
        :disabled="!areAllTagsSelected"
        v-if="currentIndex < images.length - 1"
      >
        Next
      </button>
      <button v-else>Start Training</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentIndex: 0,
      images: [
        {
          id: 1,
          url: "https://www.imt-atlantique.fr/sites/default/files/ecole/IMT_Atlantique_logo.png",
          title: "Image 1",
        },
        { id: 2, url: "path/to/image2.jpg", title: "Image 2" },
        { id: 3, url: "path/to/image3.jpg", title: "Image 3" },
        // other images...
      ],
      tagCategories: [
        {
          id: 1,
          name: "Category 1",
          tags: [
            { id: 1, name: "Tag 1.1" },
            { id: 2, name: "Tag 1.2" },
            // other tags...
          ],
        },
        {
          id: 2,
          name: "Category 2",
          tags: [
            { id: 3, name: "Tag 2.1" },
            { id: 4, name: "Tag 2.2" },
            // other tags...
          ],
        },
        // other categories...
      ],
      selectedTags: {},
      previousSelectedTags: {},
    };
  },
  computed: {
    currentImage() {
      return this.images[this.currentIndex];
    },
    areAllTagsSelected() {
      for (const category of this.tagCategories) {
        if (!this.selectedTags[category.id]) {
          return false;
        }
      }
      return true;
    },
  },
  methods: {
    nextImage() {
      if (
        this.currentIndex < this.images.length - 1 &&
        this.areAllTagsSelected
      ) {
        this.previousSelectedTags[this.currentIndex] = { ...this.selectedTags };
        this.currentIndex++;
        this.selectedTags = {}; // Reset selected tags for the next image
      }
    },
    previousImage() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
        this.selectedTags = { ...this.previousSelectedTags[this.currentIndex] };
      }
    },
  },
};
</script>

<style>
.image-gallery {
  display: flex;
  height: 100vh;
}

.image-container {
  width: 80%;
  position: relative;
}

.image {
  width: 100%;
  object-fit: cover; /* adjust image size to maintain aspect ratio and cover the container */
}

.tag-selection {
  width: 20%;
  padding: 20px;
}

.tag-category {
  margin-bottom: 10px;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.navigation-buttons {
  position: absolute;
  bottom: 10px;
  right: 10px;
}

.navigation-buttons button {
  margin-right: 10px;
}
</style>
