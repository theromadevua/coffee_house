import React, { useState } from 'react';
import { ImagePlus } from 'lucide-react';
import useProfileUpdate from '../../hooks/useProfileUpdate';
import styles from './imageGalleryModal.module.css';

interface Image {
  id: number;
  path: string;
}

interface ImageGalleryModalProps {
  images: Image[];
  onClose: () => void;
  onAddImage: () => void;
}

const ImageGalleryModal: React.FC<ImageGalleryModalProps> = ({ images, onClose, onAddImage }) => {
  const { handleImageDelete, isLoading } = useProfileUpdate();
  const [currentImageIndex, setCurrentImageIndex] = useState(0);

  if (!images.length) return null;

  return (
    <div className={styles.modalOverlay}>
      <div className={styles.modalContent}>
        <button
          className={styles.closeButton}
          onClick={onClose}
          disabled={isLoading}
        >
          ×
        </button>
        
        <div className={styles.mainImageContainer}>
          <img
            src={images[currentImageIndex].path}
            alt="Main profile"
            className={styles.mainImage}
          />
          <div className={styles.buttonGroup}>
            <button
              type="button"
              onClick={() => handleImageDelete(images[currentImageIndex].id)}
              className={styles.deleteButton}
              disabled={isLoading}
            >
              Delete Image
            </button>
            <button
              type="button"
              onClick={onAddImage}
              className={styles.addButton}
              disabled={isLoading}
            >
              <ImagePlus size={20} /> Add Image
            </button>
          </div>
        </div>

        <div className={styles.previewContainer}>
          {images.map((image, index) => (
            <div
              key={image.id}
              className={`${styles.previewImageWrapper} ${index === currentImageIndex ? styles.active : ''}`}
              onClick={() => setCurrentImageIndex(index)}
            >
              <img
                src={image.path}
                alt={`Preview ${index + 1}`}
                className={styles.previewImage}
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ImageGalleryModal;