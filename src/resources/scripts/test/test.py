import os
import tensorflow as tf
from tensorflow.keras.preprocessing.image import ImageDataGenerator 
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Flatten
from tensorflow.keras.layers import Dense
from tensorflow.keras.layers import Conv2D
from tensorflow.keras.layers import MaxPooling2D
from tensorflow.keras.callbacks import TensorBoard
from tensorflow.keras.models import load_model

with open(sys.argv[1]) as f:
    params = json.load(f)
num_classes = 2
tmp_dir = params['tmp_dir']
images = params['images']
ckp_model = params['ckp_model']

image_ids = []
image_paths = []
for key, value in d.items():
    image_paths.append(value)
    image_ids.append(id)

datagen = ImageDataGenerator(
    rescale=1./255,
    shear_range=0.2,
    zoom_range=0.2,
    horizontal_flip=True
)

def image_label_generator(image_paths, batch_size):
    num_samples = len(image_paths)
    while True:
        for offset in range(0, num_samples, batch_size):
            batch_image_paths = image_paths[offset:offset+batch_size]
            
            # 加载和增强图像
            batch_images = []
            for image_path in batch_image_paths:
                image = tf.keras.preprocessing.image.load_img(image_path, target_size=(64, 64))
                image = tf.keras.preprocessing.image.img_to_array(image)
                image = datagen.random_transform(image)
                image = datagen.standardize(image)
                batch_images.append(image)
            
            # 将图像和标签转换为数组
            batch_images = tf.stack(batch_images)
            
            yield batch_images

test_generator = image_label_generator(image_paths, 16)

# generate test data
test_images = next(test_generator)

#define the model
classifier = Sequential()
classifier.add(Conv2D(32,(3,3),input_shape=(64,64,3),activation='relu'))
classifier.add(MaxPooling2D(pool_size=(2,2),strides=2))
classifier.add(Conv2D(32,(3,3),activation='relu'))
classifier.add(MaxPooling2D(pool_size=(2,2),strides=2))
classifier.add(Flatten())
classifier.add(Dense(units=128,activation='relu'))
classifier.add(Dense(units=1,activation='sigmoid'))

adam = tf.keras.optimizers.Adam(learning_rate=0.001, beta_1=0.9, beta_2=0.999, epsilon=None, amsgrad=False)
#classifier.compile(optimizer=adam, loss='binary_crossentropy', metrics=['accuracy'])

classifier = load_model(ckp_model)

# prediction
predictions = classifier.predict(test_images)

class_labels = ['cat', 'dog']

labels = [class_labels[int(np.round(pred))] for pred in predictions]

def image_path(tmp_dir, image, suffix):
    return '{}/{}.{}'.format(tmp_dir, image.keys, suffix)

with open(self.image_path(tmp_dir, image, 'json'), 'w') as outfile:
            json.dump(labels, outfile)