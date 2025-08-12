import Image from "next/image";
import { useState } from "react";
import styles from './imageSlider.module.css';
import { ChevronLeft, ChevronRight, Hamburger } from "lucide-react";

interface ImageSliderProps {
  images: { path: string }[] | null;
  alt: string;
}

export const ImageSlider = ({ images, alt }: ImageSliderProps) => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const [imageError, setImageError] = useState(false)

  const imageList = images && images.length > 0 
    ? images 
    : [{ path: '/placeholder-image.jpg' }];

  const handlePrev = () => {
    setCurrentImageIndex((prev) => 
      prev === 0 ? imageList.length - 1 : prev - 1
    );
  };

  const handleNext = () => {
    setCurrentImageIndex((prev) => 
      prev === imageList.length - 1 ? 0 : prev + 1
    );
  };

  const handleImageError = () => {
    setImageError(true)
  }

  return (
    <div className={styles.sliderContainer}>
      <div className={styles.mainImageContainer}>
        {imageError || !imageList[currentImageIndex].path ?
         <Hamburger
            className={styles.placeholderImage}
            size={100}
            color="#999"
            aria-label="Image not available"
          />
        : <img
          src={imageList[currentImageIndex].path}
          alt={`${alt} ${currentImageIndex + 1}`}
          width={400}
          height={400}
          className={styles.mainImage}
          onError={handleImageError}
        />}
        <button 
          className={`${styles.navButton} ${styles.prevButton}`} 
          onClick={handlePrev}
          disabled={imageList.length <= 1}
          aria-label="Previous image"
        >
          <ChevronLeft className={styles.navIcon}/>
        </button>
        <button 
          className={`${styles.navButton} ${styles.nextButton}`} 
          onClick={handleNext}
          disabled={imageList.length <= 1}
          aria-label="Next image"
        >
          <ChevronRight className={styles.navIcon}/>
        </button>
      </div>

      <div className={styles.thumbnailContainer}>
        {imageList.map((image, index) => (
          <div
            key={index}
            className={`${styles.thumbnail} ${index === currentImageIndex ? styles.activeThumbnail : ''}`}
            onClick={() => setCurrentImageIndex(index)}
          >
            {imageError || !image.path ?
                <Hamburger
                    className={styles.placeholderImage}
                    size={20}
                    color="#999"
                    aria-label="Image not available"
                />
                :<img
                    onError={handleImageError}
                    src={image.path}
                    alt={`${alt} thumbnail ${index + 1}`}
                    width={80}
                    height={80}
                    className={styles.thumbnailImage}
                />
                }
          </div>
        ))}
      </div>
    </div>
  );
};