import os
import sys
import json
import numpy as np
from PIL import Image as PilImage

def pil_image(path):
    image = PilImage.open(path)
    if image.mode == 'RGBA':
        image = image.convert('RGB')
    elif image.mode == 'LA':
        image = image.convert('L')

    return image

def main(params): 
    images = params['images']
    for i, image in enumerate(images):
        image_path = image_paths[image]


if __name__ == '__main__':
    with open(sys.argv[1]) as f:
        params = json.load(f)
    main(params)
