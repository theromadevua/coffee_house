import { Upload, User } from "lucide-react";
import styles from './profileImage.module.css';

interface ProfileImageProps {
    hasImages: boolean;
    imageSrc?: string;
    onClick: () => void;
  }
  
  const ProfileImage: React.FC<ProfileImageProps> = ({ hasImages, imageSrc, onClick }) => (
    <div className={styles.profileImageContainer} onClick={onClick}>
      {hasImages ? (
        <div className={styles.imageWrapper}>
          <img className={styles.profileImage} src={imageSrc} alt="Profile" />
          <div className={styles.imageOverlay}>
            <Upload size={30} className={styles.uploadIcon} />
          </div>
        </div>
      ) : (
        <div className={styles.profileImageIcon}>
          <User size={30} />
          <div className={styles.imageOverlay}>
            <Upload size={30} className={styles.uploadIcon} />
          </div>
        </div>
      )}
    </div>
  );

  export default ProfileImage