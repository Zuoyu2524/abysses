import os
from PIL import Image as PilImage
import numpy as np
from sklearn.feature_extraction.image import extract_patches_2d

class Image(object):

    def __init__(self, id, path):
        self.id = id
        self.path = path

    def is_corrupt(self):
        image = self.pil_image()
        try:
            image.load()
        except (IOError, OSError) as e:
            print('Image #{} is corrupt!'.format(self.id))
            return True

        return False

    def pil_image(self):
        image = PilImage.open(self.path)
        if image.mode == 'RGBA':
            image = image.convert('RGB')
        elif image.mode == 'LA':
            image = image.convert('L')

        return image

    def _get_resized_image(self):
        image = self.pil_image()
        return image.resize((256, 256), PilImage.BILINEAR)

    def extract_pca_features(self):
        return np.array(self._get_resized_image()).flatten()

    def extract_features(self):
        resized_image = np.array(self._get_resized_image().convert('L'))
        e = np.sum(resized_image)

        return [e]