import numpy as np
from Image import Image
from concurrent.futures import ProcessPoolExecutor, as_completed

class ImageCollection(object):
    def __init__(self, items, executor=None):
        if type(items) == dict:
            self.images = [Image(id, path) for id, path in items.items()]
        else:
            self.images = items

        self.executor = executor if executor != None else ProcessPoolExecutor()

    def __getitem__(self, key):
        return self.images[key]

    def __len__(self):
        return len(self.images)

    def prune_corrupt_images(self):
        self.images = [image for image in self.images if not image.is_corrupt()]

    def set_executor(self, executor):
        self.executor = executor