import React, { RefObject } from 'react';
import { ImagePlus } from 'lucide-react';
import styles from './imageSection.module.css';

interface ImageSectionProps {
  user: any;
  isLoading: boolean;
  fileInputRef: RefObject<HTMLInputElement | null>;
  onIconClick: () => void;
}

const ImageSection: React.FC<ImageSectionProps> = ({ user, isLoading, fileInputRef, onIconClick }) => {
  return (
    <div className={styles.imageSection}>
      <button
        type="button"
        onClick={onIconClick}
        className={styles.imageIconButton}
        disabled={isLoading}
      >
        {user.gallery?.images?.length > 0 ? (
          <img
            src={user.gallery.images[0].path}
            alt="Profile"
            className={styles.profileImage}
          />
        ) : (
          <ImagePlus className={styles.addImageIcon} size={32} />
        )}
      </button>
      <input
        type="file"
        accept="image/*"
        onChange={(e) => fileInputRef.current?.dispatchEvent(new Event('change', { bubbles: true }))}
        className={styles.hiddenInput}
        ref={fileInputRef}
        disabled={isLoading}
      />
    </div>
  );
};

export default ImageSection;
