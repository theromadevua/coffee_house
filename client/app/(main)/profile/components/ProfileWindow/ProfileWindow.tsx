'use client';

import { useState, useRef, useEffect } from 'react';
import { useProfile } from '../../hooks/useProfile';
import styles from './profileWindow.module.css';
import { Trash } from 'lucide-react';
import useAuthStore from '@/store/authStore';

interface Image {
  id: string;
  path: string;
}

const ProfileWindow = () => {
  const { user, handleOpenProfileImage, addImage } = useProfile();
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const { deleteImage } = useAuthStore();
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [images, setImages] = useState<Image[]>([]);

  useEffect(() => {
    setImages(user?.gallery?.images || []);
  }, [user]);

  const handleImageClick = (index: number) => {
    setCurrentImageIndex(index);
  };

  const handleAddImage = (event: React.ChangeEvent<HTMLInputElement>) => {
    if (event.target.files && event.target.files[0]) {
      addImage(event.target.files[0]);
    }
  };

  const handleDeleteImage = (id: string, index: number) => {
    deleteImage(id);

    if(index !== 0 && images.length > 1) {
      setCurrentImageIndex(index - 1)
    }else{
      handleOpenProfileImage()
    }
  };

  const triggerFileInput = () => {
    fileInputRef.current?.click();
  };

  return (
    <div className={styles.profileWindow} onClick={handleOpenProfileImage}>
      <div className={styles.profileContent} onClick={(e) => e.stopPropagation()}>
        <div className={styles.profileImageContainer}>
          {images.length > 0 ? (
            <img
              src={images[currentImageIndex]?.path}
              className={styles.profileImage}
              alt="Profile image"
            />
          ) : (
            <div className={styles.noImages}>
              No images available
              <button
                className={styles.addButton}
                onClick={triggerFileInput}
                aria-label="Add new image"
              >
                +
              </button>
            </div>
          )}
        </div>

        <div className={styles.controls}>
          <div className={styles.thumbnailContainer}>
            {images.map((image, index) => (
              <div key={image.id} className={styles.thumbnailWrapper}>
                <img
                  src={image.path}
                  className={`${styles.thumbnail} ${
                    index === currentImageIndex ? styles.activeThumbnail : ''
                  }`}
                  onClick={() => handleImageClick(index)}
                  alt={`Thumbnail ${index + 1}`}
                />
                <button
                  className={styles.deleteButton}
                  onClick={() => handleDeleteImage(image.id, index)}
                  aria-label={`Delete image ${index + 1}`}
                >
                  <Trash size={16} />
                </button>
              </div>
            ))}
          </div>
          <button
            className={styles.addButton}
            onClick={triggerFileInput}
            aria-label="Add new image"
          >
            +
          </button>
          <input
            type="file"
            ref={fileInputRef}
            className={styles.fileInput}
            accept="image/*"
            onChange={handleAddImage}
          />
        </div>
      </div>
    </div>
  );
};

export default ProfileWindow;