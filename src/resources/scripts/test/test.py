"""import numpy as np
import os
import json
import sys
from PIL import Image
from concurrent.futures import ThreadPoolExecutor, wait
from concurrent.futures import ProcessPoolExecutor, as_completed
from ImageCollection import ImageCollection


class DetectionRunner(object):

    def __init__(self, params):
        # Dict of image IDs and file paths to the images to process.
        self.images = params['images']
        # Path to the directory to store temporary files.
        self.tmp_dir = params['tmp_dir']

    def postprocess_map(self, image, threshold):
        saliency_map = np.load(self.image_path(image, 'npy'))
        binary_map = np.where(saliency_map > threshold, 255, 0).astype(np.uint8)
        mask = np.zeros_like(binary_map)
        points = []

        with open(self.image_path(image, 'json'), 'w') as outfile:
            json.dump(points, outfile)
        os.remove(self.image_path(image, 'npy'))

    def process_cluster(self, cluster):

        for i, image in enumerate(cluster):
            np.save(self.image_path(image, 'npy'), image)

        return 0

    def run(self):
        full_executor = ThreadPoolExecutor(max_workers=1)

        images = ImageCollection(self.images, executor=full_executor)
        images.prune_corrupt_images()
        total_images = len(images)

        if total_images == 0:
            print('No image files to process.')
        
        clusters = [images]

        jobs = []

        post_executor = full_executor

        for i, cluster in enumerate(clusters):
            threshold = self.process_cluster(cluster)
            jobs.extend([post_executor.submit(self.postprocess_map, image, threshold) for image in cluster])

        wait(jobs)


    def image_path(self, image, suffix):
        return '{}/{}.{}'.format(self.tmp_dir, image.id, suffix)


with open(sys.argv[1]) as f:
    params = json.load(f)



runner = DetectionRunner(params)
runner.run()"""

import logging
from concurrent.futures import ThreadPoolExecutor, wait
import json
import sys
import tensorflow as tf

def run():
    # Configure logging
    #log_file = 'log.txt'
    #logging.basicConfig(filename=log_file, level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

    full_executor = ThreadPoolExecutor(max_workers=1)
    try:
        with open(sys.argv[1]) as f:
            params = json.load(f)

        images = params['images']
        labels = []

        tmp_dir = params['tmp_dir']
        jobs = []

        print(images)
        for i, image in enumerate(images):
            jobs.extend([full_executor.submit(image)])
            wait(jobs)
            image_path = '{}/{}.{}'.format(tmp_dir, image, 'json')
            print(type(image))
            labels = [int(image), 'dogs']
            with open(image_path, 'w') as outfile:
                json.dump(labels, outfile)
        print("success")
        #logging.info("Success")  # Log success message
        return "success"

    except Exception as e:
        logging.error(f"Error: {str(e)}")  # Log error message
        raise e

results = run()

