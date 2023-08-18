import logging
from concurrent.futures import ThreadPoolExecutor, wait
import json
import sys
import os
from PIL import Image as PilImage
from sklearn.neural_network import MLPClassifier
import numpy as np

def pil_image(path):
        image = PilImage.open(path)
        if image.mode == 'RGBA':
            image = image.convert('RGB')
        elif image.mode == 'LA':
            image = image.convert('L')
        image = np.array(image)
        image.resize(1,250)
        return image

def run():
    full_executor = ThreadPoolExecutor(max_workers=2)
    try:
        with open(sys.argv[1]) as f:
            params = json.load(f)

        images = params['images']

        tmp_dir = params['tmp_dir']

        i = 1
        for key, value in images.items():
            print(i)
            if(i == 1):
                x = pil_image(images[key])
                i = 0
            else:
                image = pil_image(images[key])
                x = np.vstack((x, image))
        print(x.shape)
        cats = np.zeros(21)
        dogs = np.ones(21)
        labels = np.concatenate((cats, dogs))
        model = MLPClassifier(hidden_layer_sizes=(100,),max_iter=100, random_state=42)
        model.fit(x,labels)

        for key, value in images.items():
            x = pil_image(images[key])
            y_pred = model.predict(x)
            image_path = '{}/{}.{}'.format(tmp_dir, key, 'json')
            if(y_pred == 0):
                labels = [int(key), 'cat']
            else:
                labels = [int(key), 'dog']
            with open(image_path, 'w') as outfile:
                json.dump(labels, outfile)
        print("success")
        return "success"

    except Exception as e:
        logging.error(f"Error: {str(e)}")  # Log error message
        raise e

results = run()

